<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(\Nwidart\Modules\LaravelModulesServiceProvider::class);
    }

    public function boot(): void
    {
        // Performance optimizations
        $this->configureDatabaseOptimizations();
        $this->configureQueryOptimizations();
        
        // Temporarily disable all functionality
        // return;
    }

    private function configureDatabaseOptimizations(): void
    {
        // Enable query logging in development
        if (config('app.debug')) {
            DB::listen(function ($query) {
                if ($query->time > 100) { // Log slow queries (>100ms)
                    \Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]);
                }
            });
        }

        // Optimize database connection
        DB::connection()->getPdo()->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        DB::connection()->getPdo()->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
    }

    private function configureQueryOptimizations(): void
    {
        // Prevent N+1 queries by requiring explicit eager loading
        // Model::preventLazyLoading(!app()->isProduction());
        
        // Add query timeout (only for MySQL/MariaDB)
        $connection = config('database.default');
        if (in_array($connection, ['mysql', 'mariadb'])) {
            try {
                DB::statement('SET SESSION wait_timeout = 300');
                DB::statement('SET SESSION interactive_timeout = 300');
            } catch (\Exception $e) {
                // Ignore if not supported
            }
        }
    }
}