<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Correct view path
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'core');

        // Optional Blade component registration
        Blade::componentNamespace('Modules\\Core\\View\\Components', 'core');

        // Correct route path
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    public function register()
    {
        // Bind services if needed
    }
}
