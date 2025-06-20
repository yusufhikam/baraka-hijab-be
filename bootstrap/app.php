<?php

use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            StartSession::class,
            ShareErrorsFromSession::class,
        ]);

        $middleware->api([
            StartSession::class,
            CorsMiddleware::class,
        ]);

        $middleware->alias([
            'role' => EnsureUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();



