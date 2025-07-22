<?php

namespace Modules\Library\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Library\Providers\RouteServiceProvider;

class LibraryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $viewPath = module_path('Library', 'resources/views');
        \Log::info('LibraryServiceProvider booted! View path: ' . $viewPath);
        $this->loadViewsFrom($viewPath, 'library');
        // Register module resources, routes, etc.
        $this->autoGeneratePermissions('library');
    }

    protected function autoGeneratePermissions($module)
    {
        $actions = ['view', 'create', 'edit', 'delete', 'manage'];
        foreach ($actions as $action) {
            $permName = $module . '.' . $action;
            \App\Models\Permission::firstOrCreate(
                ['name' => $permName],
                ['display_name' => ucfirst($action) . ' ' . ucfirst($module), 'module' => ucfirst($module)]
            );
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        // Bind services into the container.
    }
} 