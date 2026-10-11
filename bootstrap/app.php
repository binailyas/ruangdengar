<?php

use App\Http\Middleware\EnsureStudent;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Render menghentikan TLS di proxy; percayai header X-Forwarded-* agar
        // Laravel tahu request aslinya HTTPS (asset(), url(), route() jadi https://).
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'student' => EnsureStudent::class,
            'feature' => \App\Http\Middleware\FeatureGate::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('welcome'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();