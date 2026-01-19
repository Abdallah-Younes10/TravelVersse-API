<?php

namespace App\Http;

use App\Http\Middleware\VerifyCategoriesCount;
use App\Http\Middleware\VerifyIsAdmin;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // ← هذا مهم لو تستخدم المتصفح
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
],

    ];

    protected $routeMiddleware = [
        'guest.only' => \App\Http\Middleware\GuestOnly::class,
        'usertype' => \App\Http\Middleware\CheckUserType::class,
    ];
}
