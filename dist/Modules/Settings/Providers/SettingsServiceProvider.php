<?php

namespace Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Register module resources, routes, etc.
        $this->loadViewsFrom(module_path('Settings', 'resources/views'), 'settings');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind services into the container.
    }
} 