<?php

namespace Modules\Transport\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $moduleNamespace = 'Modules\\Transport\\Http\\Controllers';

    public function boot()
    {
        parent::boot();
        $this->map();
    }

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(module_path('Transport', '/routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(module_path('Transport', '/routes/api.php'));
    }
} 