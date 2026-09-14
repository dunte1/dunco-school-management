<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Define a simple module_path helper function
        if (!function_exists('module_path')) {
            function module_path(string $name, string $path = ''): string {
                return base_path('Modules/' . $name) . ($path ? DIRECTORY_SEPARATOR . $path : '');
            }
        }
        
        // Define a simple module helper function
        if (!function_exists('module')) {
            function module(string $name, bool $instance = false): bool {
                // Check if module exists and is enabled based on modules_statuses.json
                $statusFile = base_path('modules_statuses.json');
                if (file_exists($statusFile)) {
                    $statuses = json_decode(file_get_contents($statusFile), true);
                    return isset($statuses[$name]) && $statuses[$name] === true;
                }
                return false;
            }
        }
        
        // Manually register module service providers in a simple way
        // $this->registerModuleProviders(); // Temporarily disabled to fix cache binding issue
    }
    
    protected function registerModuleProviders()
    {
        // Get enabled modules from modules_statuses.json
        $statusFile = base_path('modules_statuses.json');
        if (!file_exists($statusFile)) {
            return;
        }
        
        $statuses = json_decode(file_get_contents($statusFile), true);
        if (!$statuses) {
            return;
        }
        
        // Register only enabled modules
        foreach ($statuses as $moduleName => $enabled) {
            if ($enabled && $moduleName !== 'Transport') { // Transport is already registered
                $providerClass = "Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";
                if (class_exists($providerClass)) {
                    try {
                        $this->app->register($providerClass);
                    } catch (Exception $e) {
                        // Log error but continue with other modules
                        \Log::warning("Failed to register module {$moduleName}: " . $e->getMessage());
                    }
                }
            }
        }
    }

    public function boot(): void
    {
        // Performance optimizations - temporarily disabled to fix database binding issues
        // $this->configureDatabaseOptimizations();
        // $this->configureQueryOptimizations();

        // Fix pagination view factory resolver
        \Illuminate\Pagination\AbstractPaginator::viewFactoryResolver(function () {
            return app('view');
        });

        // Set default pagination styling to Bootstrap 5
        \Illuminate\Pagination\AbstractPaginator::useBootstrapFive();

        // Add pagination view namespace
        view()->addNamespace('pagination', resource_path('views/vendor/pagination'));

        // Define custom Blade directive used in views like @role('admin') ... @endrole
        Blade::if('role', function ($role) {
            return auth()->check()
                && method_exists(auth()->user(), 'hasRole')
                && auth()->user()->hasRole($role);
        });

        $this->bootEnabledModules();
    }

    /**
     * Register view namespaces and load migrations for enabled modules without
     * relying on the (disabled) nwidart/laravel-modules package.
     *
     * Path lookups are case-insensitive so this works on both Windows and Linux.
     */
    protected function bootEnabledModules(): void
    {
        $statusFile = base_path('modules_statuses.json');
        if (!file_exists($statusFile)) {
            return;
        }

        $statuses = json_decode(file_get_contents($statusFile), true) ?: [];

        foreach ($statuses as $moduleName => $enabled) {
            if (!$enabled) {
                continue;
            }

            $moduleBasePath = base_path('Modules/' . $moduleName);
            if (!is_dir($moduleBasePath)) {
                continue;
            }

            // View namespaces: register lowercase, exact and snake_case aliases so
            // both `view('finance::...')` and `view('Finance::...')` resolve.
            $viewsPath = $this->findFirstDir($moduleBasePath, ['resources/views', 'Resources/Views']);
            if ($viewsPath !== null) {
                $namespaces = array_unique([
                    strtolower($moduleName),
                    $moduleName,
                    \Illuminate\Support\Str::snake($moduleName),
                ]);

                foreach ($namespaces as $namespace) {
                    view()->addNamespace($namespace, $viewsPath);
                }
            }

            // Migrations (case-insensitive path).
            $migrationsPath = $this->findFirstDir($moduleBasePath, ['database/migrations', 'Database/Migrations']);
            if ($migrationsPath !== null) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        }
    }

    /**
     * Return the first existing directory among the given relative candidates.
     */
    protected function findFirstDir(string $basePath, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            $path = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $candidate);
            if (is_dir($path)) {
                return $path;
            }
        }

        return null;
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