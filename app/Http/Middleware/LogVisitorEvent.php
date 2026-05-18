<?php

namespace App\Http\Middleware;

use App\Models\VisitorEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogVisitorEvent
{
    /**
     * Search-engine hosts whose referrers we classify as "organic search".
     */
    private const SEARCH_HOSTS = [
        'google.', 'bing.com', 'duckduckgo.com', 'yahoo.', 'yandex.',
        'baidu.com', 'ecosia.org', 'startpage.com', 'qwant.com', 'brave.com',
    ];

    private const SOCIAL_HOSTS = [
        'facebook.com', 'instagram.com', 'twitter.com', 'x.com', 't.co',
        'linkedin.com', 'youtube.com', 'youtu.be', 'tiktok.com', 'pinterest.',
        'reddit.com', 'whatsapp.com',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log successful HTML GETs.
        if (! $this->shouldLog($request, $response)) {
            return $response;
        }

        try {
            $this->record($request);
        } catch (\Throwable $e) {
            // Never break the request for analytics failures.
            report($e);
        }

        return $response;
    }

    private function shouldLog(Request $request, Response $response): bool
    {
        if ($request->method() !== 'GET') {
            return false;
        }
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            return false;
        }
        $contentType = (string) $response->headers->get('Content-Type', '');
        if ($contentType !== '' && ! str_contains($contentType, 'text/html')) {
            return false;
        }
        // Skip admin area, asset requests, health checks, etc.
        $path = '/'.ltrim($request->path(), '/');
        $adminPrefix = '/'.trim((string) config('cms.admin_prefix', 'admin'), '/');
        if ($adminPrefix !== '/' && (str_starts_with($path, $adminPrefix.'/') || $path === $adminPrefix)) {
            return false;
        }
        if (in_array($path, ['/up', '/favicon.ico'], true)) {
            return false;
        }
        if (preg_match('#^/(storage|css|js|assets|fonts)/#i', $path)) {
            return false;
        }
        // Skip obvious bots.
        $ua = (string) $request->userAgent();
        if ($ua === '' || preg_match('/(bot|crawler|spider|preview|monitor|curl|wget|axios|python-requests|ahrefs|semrush)/i', $ua)) {
            return false;
        }
        return true;
    }

    private function record(Request $request): void
    {
        if (! VisitorEvent::isAvailable()) {
            return;
        }

        $sessionId = $this->sessionId($request);
        $ua = (string) $request->userAgent();
        $ip = (string) $request->ip();
        $ipHash = hash('sha256', $ip.'|'.config('app.key'));
        $uaHash = hash('sha256', $ua.'|'.config('app.key'));

        $referer = (string) $request->headers->get('Referer', '');
        $refererHost = $referer ? parse_url($referer, PHP_URL_HOST) : null;
        $appHost = $request->getHost();
        $isInternal = $refererHost && stripos($refererHost, $appHost) !== false;
        $source = $this->classifySource($refererHost, $isInternal);

        // Determine if this is a new session (first event in this session_id today)
        // and a new visitor (no prior event with this ip+ua in last 30 days).
        $sessionExisting = VisitorEvent::query()
            ->where('session_id', $sessionId)
            ->whereDate('created_at', now()->toDateString())
            ->exists();
        $isNewSession = ! $sessionExisting;

        $visitorExisting = VisitorEvent::query()
            ->where('ip_hash', $ipHash)
            ->where('user_agent_hash', $uaHash)
            ->where('created_at', '>=', now()->subDays(30))
            ->exists();
        $isNewVisitor = $isNewSession && ! $visitorExisting;

        $eventIndex = VisitorEvent::query()
            ->where('session_id', $sessionId)
            ->whereDate('created_at', now()->toDateString())
            ->count() + 1;

        VisitorEvent::create([
            'session_id' => $sessionId,
            'path' => Str::limit('/'.ltrim($request->path(), '/'), 500, ''),
            'referer' => $referer !== '' ? Str::limit($referer, 500, '') : null,
            'referrer_host' => $refererHost ? Str::limit($refererHost, 200, '') : null,
            'source' => $source,
            'user_agent_hash' => $uaHash,
            'ip_hash' => $ipHash,
            'is_new_session' => $isNewSession,
            'is_new_visitor' => $isNewVisitor,
            'session_event_index' => $eventIndex,
            'created_at' => Carbon::now(),
        ]);
    }

    private function sessionId(Request $request): string
    {
        try {
            return (string) ($request->hasSession() ? $request->session()->getId() : Str::uuid());
        } catch (\Throwable) {
            return (string) Str::uuid();
        }
    }

    private function classifySource(?string $host, bool $isInternal): string
    {
        if (! $host || $host === '') {
            return 'direct';
        }
        if ($isInternal) {
            return 'internal';
        }
        $lower = strtolower($host);
        foreach (self::SEARCH_HOSTS as $needle) {
            if (str_contains($lower, $needle)) {
                return 'organic';
            }
        }
        foreach (self::SOCIAL_HOSTS as $needle) {
            if (str_contains($lower, $needle)) {
                return 'social';
            }
        }
        return 'referral';
    }
}
