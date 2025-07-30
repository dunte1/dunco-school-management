<?php

namespace Modules\Portal\Providers;

use Illuminate\Support\ServiceProvider;

class PortalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'portal');
        if (\Schema::hasTable('permissions')) {
            $this->autoGeneratePermissions('portal');
        }
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
        // Bind services into the container.
    }
} 