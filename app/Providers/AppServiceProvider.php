<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Laravel\Module;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register modules
        $this->app->register(\Nwidart\Modules\LaravelModulesServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Blade directive for checking permissions
        Blade::if('permission', function ($permission) {
            return Auth::check() && Auth::user()->hasPermission($permission);
        });

        // Blade directive for checking roles
        Blade::if('role', function ($role) {
            return Auth::check() && Auth::user()->hasRole($role);
        });

        // Blade directive for checking any permission
        Blade::if('anypermission', function ($permissions) {
            return Auth::check() && Auth::user()->hasAnyPermission($permissions);
        });

        // Blade directive for checking any role
        Blade::if('anyrole', function ($roles) {
            return Auth::check() && Auth::user()->hasAnyRole($roles);
        });
    }
}
