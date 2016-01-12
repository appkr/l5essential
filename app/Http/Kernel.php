<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,

//        Laravel 5.2 Global middlewares, but will not be used for this project
//        'web' => [
//            \App\Http\Middleware\EncryptCookies::class,
//            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//            \Illuminate\Session\Middleware\StartSession::class,
//            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//            \App\Http\Middleware\VerifyCsrfToken::class,
//        ],
//
//        'api' => [
//            'throttle:60,1',
//        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'        => \App\Http\Middleware\Authenticate::class,
        'auth.basic'  => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'       => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'    => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'throttle.api'=> \App\Http\Middleware\ThrottleApiRequests::class,
        'role'        => \Bican\Roles\Middleware\VerifyRole::class,
        'author'      => \App\Http\Middleware\AuthorOnly::class,
        'jwt.auth'    => \App\Http\Middleware\GetUserFromToken::class,
        'jwt.refresh' => \App\Http\Middleware\RefreshToken::class,
    ];
}
