<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        $this->routes(function () {
            // Note: web.php is already loaded by bootstrap/app.php withRouting(web:).
            // Only load API routes here to avoid duplicate registration.

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Module API routes are opt-in (see routes/modules-api.php).
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/modules-api.php'));
        });
    }
}
