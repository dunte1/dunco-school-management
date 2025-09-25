<?php

namespace Modules\Hostel\Providers;

use Illuminate\Support\ServiceProvider;

class HostelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hostel');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind services into the container.
    }
} 