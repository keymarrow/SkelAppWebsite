<?php

namespace App\Http\Middleware;

use App\Models\VisitorEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogVisitorEvent
{
    /**
     * Persistent visitor cookie. Lives 2 years so the same device is
     * recognised as a returning visitor across sessions without depending on
     * volatile signals like IP address.
     */
    private const VISITOR_COOKIE = 'skelapp_vid';
    private const VISITOR_COOKIE_MINUTES = 60 * 24 * 365 * 2; // ~2 years

    /**
     * Search-engine hosts whose referrers we classify as "organic search".
     */
    private const SEARCH_HOSTS = [
        'google.', 'bing.com', 'duckduckgo.com', 'yahoo.', 'yandex.',
        'baidu.com', 'ecosia.org', 'startpage.com', 'qwant.com', 'brave.com',
        'search.brave.com', 'kagi.com', 'searx.', 'mojeek.com', 'naver.com',
        'seznam.cz',
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

        // Skip the admin app entirely — by host when admin lives on its own
        // subdomain (e.g. admin.skelapp.tz) and by path prefix when admin is
        // served from /admin/* on the public host.
        $adminHost = (string) config('cms.admin_host');
        if ($adminHost !== '' && strcasecmp($request->getHost(), $adminHost) === 0) {
            return false;
        }

        $path = '/'.ltrim($request->path(), '/');
        $adminPrefix = '/'.trim((string) config('cms.admin_prefix', 'admin'), '/');
        if ($adminPrefix !== '/' && (str_starts_with($path, $adminPrefix.'/') || $path === $adminPrefix)) {
            return false;
        }
        if (\in_array($path, ['/up', '/favicon.ico'], true)) {
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
        $appKey = (string) config('app.key');
        $ipHash = hash('sha256', $ip.'|'.$appKey);
        $uaHash = hash('sha256', $ua.'|'.$appKey);

        // Resolve (or mint) a persistent device id. This cookie is the
        // primary signal for distinguishing new vs returning visitors — a
        // device that has previously been here will already carry it.
        [$visitorId, $cookieIssued] = $this->resolveVisitorId($request);
        $visitorIdHash = hash('sha256', $visitorId.'|'.$appKey);

        $referer = (string) $request->headers->get('Referer', '');
        $refererHost = $referer ? parse_url($referer, PHP_URL_HOST) : null;
        $host = $request->getHost();
        $isInternal = $refererHost && stripos($refererHost, $host) !== false;
        $source = $this->classifySource($refererHost, $isInternal);

        $today = now()->toDateString();

        // A "new session" is the first event in this session_id today.
        $sessionExisting = VisitorEvent::query()
            ->where('session_id', $sessionId)
            ->whereDate('created_at', $today)
            ->exists();
        $isNewSession = ! $sessionExisting;

        // A "new visitor" is one we have never recorded before — match on the
        // persistent cookie first, fall back to ip+ua for users whose cookie
        // was cleared. No time window: once known, always returning.
        $visitorPreviouslySeen = VisitorEvent::query()
            ->where(function ($q) use ($visitorIdHash, $ipHash, $uaHash) {
                $q->where('visitor_id_hash', $visitorIdHash)
                    ->orWhere(function ($q2) use ($ipHash, $uaHash) {
                        $q2->where('ip_hash', $ipHash)
                            ->where('user_agent_hash', $uaHash);
                    });
            })
            ->exists();
        $isNewVisitor = $isNewSession && ! $visitorPreviouslySeen;

        $eventIndex = VisitorEvent::query()
            ->where('session_id', $sessionId)
            ->whereDate('created_at', $today)
            ->count() + 1;

        VisitorEvent::create([
            'session_id' => $sessionId,
            'path' => Str::limit('/'.ltrim($request->path(), '/'), 500, ''),
            'host' => Str::limit($host, 200, ''),
            'referer' => $referer !== '' ? Str::limit($referer, 500, '') : null,
            'referrer_host' => $refererHost ? Str::limit($refererHost, 200, '') : null,
            'source' => $source,
            'user_agent_hash' => $uaHash,
            'ip_hash' => $ipHash,
            'visitor_id_hash' => $visitorIdHash,
            'is_new_session' => $isNewSession,
            'is_new_visitor' => $isNewVisitor,
            'session_event_index' => $eventIndex,
            'created_at' => Carbon::now(),
        ]);

        if ($cookieIssued) {
            Cookie::queue(
                self::VISITOR_COOKIE,
                $visitorId,
                self::VISITOR_COOKIE_MINUTES,
                '/',
                null,
                $request->isSecure(),
                true,
                false,
                'lax'
            );
        }
    }

    /**
     * @return array{0:string,1:bool} [visitorId, shouldIssueCookie]
     */
    private function resolveVisitorId(Request $request): array
    {
        $existing = $request->cookie(self::VISITOR_COOKIE);
        if (is_string($existing) && preg_match('/^[a-f0-9]{32}$/', $existing)) {
            return [$existing, false];
        }
        return [bin2hex(random_bytes(16)), true];
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
