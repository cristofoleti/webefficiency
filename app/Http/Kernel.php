<?php
namespace Webefficiency\Http;

use Illuminate\Session\Middleware\StartSession;
use Webefficiency\Http\Middleware\EncryptCookies;
use Webefficiency\Http\Middleware\CompanyMiddleware;
use Webefficiency\Http\Middleware\AdminMiddleware;
use Webefficiency\Http\Middleware\GroupAdminMiddleware;
use Webefficiency\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => 'Webefficiency\Http\Middleware\Authenticate',
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest' => 'Webefficiency\Http\Middleware\RedirectIfAuthenticated',
        'company' => CompanyMiddleware::class,
        'admin' => AdminMiddleware::class,
        'group_admin' => GroupAdminMiddleware::class,
    ];
}
