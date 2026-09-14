<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware
        $middleware->append(App\Http\Middleware\SecurityHeaders::class);

        // Force HTTPS in production
        $middleware->append(App\Http\Middleware\ForceHttps::class);

        // Ensure permission/role changes apply immediately after refresh/login
        $middleware->appendToGroup('web', App\Http\Middleware\ClearPermissionCache::class);
        $middleware->appendToGroup('api', App\Http\Middleware\ClearPermissionCache::class);

        // Optional useful middleware
        $middleware->appendToGroup('web', App\Http\Middleware\CheckUserActive::class);
        $middleware->appendToGroup('web', App\Http\Middleware\PerformanceMonitor::class);
        $middleware->appendToGroup('api', App\Http\Middleware\PerformanceMonitor::class);

        // Route middleware aliases (previously undefined, causing 500s / no authz)
        $middleware->alias([
            'role' => App\Http\Middleware\EnsureUserHasRole::class,
            'permission' => App\Http\Middleware\CheckPermission::class,
            'admin' => App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Basic exception handling configuration
    })
    ->withProviders([
        // Essential Laravel Framework Service Providers for Laravel 12
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Illuminate\Events\EventServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Routing\RoutingServiceProvider::class,

        // Application Service Providers
        App\Providers\AppServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\AuthServiceProvider::class,

        // NOTE: Module package and module providers disabled for now to avoid
        // cache binding and boot conflicts on Laravel 12.
        // Nwidart\Modules\LaravelModulesServiceProvider::class,
        // Modules\Core\Providers\CoreServiceProvider::class,
        // Modules\Academic\Providers\AcademicServiceProvider::class,
        // Modules\Examination\Providers\ExaminationServiceProvider::class,
        // Modules\ChatBot\Providers\ChatBotServiceProvider::class,
    ])
    ->create();
