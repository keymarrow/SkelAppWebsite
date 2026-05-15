<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
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
        //
    })->create();
