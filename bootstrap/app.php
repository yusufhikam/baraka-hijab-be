<?php

use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\EnsureCheckoutActive;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserRole;
use App\Http\Middleware\JWTCookieMiddleware;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(HandleCors::class);
        $middleware->web([
            StartSession::class,
            ShareErrorsFromSession::class,
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
        ]);

        // uncomment this if use session stateful 
        // $middleware->statefulApi();

        $middleware->api([
            // JWTCookieMiddleware::class,
            // \PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class,

        
            CorsMiddleware::class, // jika kamu punya custom
                // 'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            
        ]);

        $middleware->alias([
            'role' => EnsureUserRole::class,
            'jwt.cookie' => JWTCookieMiddleware::class,
            'checkout.active' => EnsureCheckoutActive::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();