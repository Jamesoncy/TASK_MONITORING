<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class   
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'LoginField' => \App\Http\Middleware\LoginMiddleware::class,
        'CreateUser' => \App\Http\Middleware\CreateUserMiddleware::class,
        'CheckAdmin' => \App\Http\Middleware\CheckAdminMiddleware::class,
        'CheckRole' => \App\Http\Middleware\CheckRoleMiddleware::class,
        'CheckProject' => \App\Http\Middleware\CheckProjectMiddleware::class,
        'CheckProjectExist' => \App\Http\Middleware\CheckProjectExistMiddleware::class,
        'CheckProjectApproval' => \App\Http\Middleware\CheckApprovalMiddleware::class,
        'CheckProjectApproved' => \App\Http\Middleware\CheckProjectApprovedMiddleware::class,
        'CheckProjectSuccess' => \App\Http\Middleware\CheckProjectSuccessMiddleware::class,
        "check_project_success" => \App\Http\Middleware\CheckProjectApprovalMiddleware::class,
        'check_project_complete' => \App\Http\Middleware\CheckCompleteTimeAllocationMiddleware::class
    ];
}
