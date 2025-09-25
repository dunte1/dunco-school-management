<?php

namespace Modules\HR\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class HRServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hr');
        if (\Schema::hasTable('permissions')) {
            $this->autoGeneratePermissions('hr');
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

    public function register()
    {
        // Register bindings, config, etc.
    }
} 