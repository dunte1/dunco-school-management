<?php

namespace Modules\Timetable\Providers;

use Illuminate\Support\ServiceProvider;

class TimetableServiceProvider extends ServiceProvider
{
    protected string $name = 'Timetable';
    protected string $nameLower = 'timetable';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        \Log::info('TimetableServiceProvider booted!');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
        if (\Schema::hasTable('permissions')) {
            $this->autoGeneratePermissions('timetable');
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Register other providers if needed, e.g. RouteServiceProvider
        // $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->name, 'config/config.php') => config_path($this->nameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->name, 'config/config.php'), $this->nameLower
        );
    }

    protected function registerViews(): void
    {
        $viewPath = __DIR__.'/../resources/views';
        $this->loadViewsFrom($viewPath, 'Timetable');
        $this->loadViewsFrom($viewPath, 'timetable');
    }

    protected function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->nameLower)) {
                $paths[] = $path . '/modules/' . $this->nameLower;
            }
        }
        return $paths;
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
} 