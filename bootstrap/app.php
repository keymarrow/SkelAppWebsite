<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Behind Cloudflare + nginx: trust forwarded headers so Laravel sees
        // the real visitor IP and knows the connection is HTTPS.
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            \App\Http\Middleware\LogVisitorEvent::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request): string {
            $adminHost = config('cms.admin_host');
            $adminPrefix = '/'.trim((string) config('cms.admin_prefix', 'admin'), '/');

            $isAdminRequest = filled($adminHost)
                ? $request->getHost() === $adminHost
                : $request->is(ltrim($adminPrefix, '/')) || $request->is(ltrim($adminPrefix, '/').'/*');

            return $isAdminRequest
                ? route('admin.login')
                : route('contact.show');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A CSRF failure renders Laravel's bare "419 PAGE EXPIRED" screen, which
        // is a dead end — the token dies with the session (SESSION_LIFETIME), so
        // leaving a form open long enough guarantees it. Bounce back to the form
        // with a readable message instead; the retry then carries a fresh token.
        // Input is preserved so a half-written CMS edit is not lost.
        // Registered against the status rather than TokenMismatchException:
        // prepareException() rewrites that into a 419 HttpException before
        // render callbacks run, so a callback typed to the original class never
        // matches. Returning null for other statuses falls through to default
        // handling.
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            $message = 'Your session expired. Please try again.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 419);
            }

            return redirect()
                ->back()
                ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                ->with('error', $message);
        });
    })->create();
