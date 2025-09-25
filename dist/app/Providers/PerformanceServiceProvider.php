<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\PerformanceService;

class PerformanceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PerformanceService::class, function ($app) {
            return new PerformanceService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('performance.monitoring.enabled')) {
            $this->configurePerformanceMonitoring();
        }
    }

    /**
     * Configure performance monitoring
     */
    private function configurePerformanceMonitoring(): void
    {
        // Monitor slow queries
        if (config('performance.monitoring.log_slow_queries')) {
            DB::listen(function ($query) {
                $time = $query->time;
                $threshold = config('performance.database.slow_query_threshold', 100);
                
                if ($time > $threshold) {
                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $time . 'ms',
                        'threshold' => $threshold . 'ms',
                    ]);
                    
                    Cache::increment('slow_queries_count');
                }
            });
        }

        // Monitor memory usage
        $this->monitorMemoryUsage();
    }

    /**
     * Monitor memory usage
     */
    private function monitorMemoryUsage(): void
    {
        $memoryLimit = ini_get('memory_limit');
        $currentMemory = memory_get_usage(true);
        $peakMemory = memory_get_peak_usage(true);
        
        // Log if memory usage is high
        if ($currentMemory > 50 * 1024 * 1024) { // 50MB
            Log::info('High memory usage detected', [
                'current' => number_format($currentMemory / 1024 / 1024, 2) . ' MB',
                'peak' => number_format($peakMemory / 1024 / 1024, 2) . ' MB',
                'limit' => $memoryLimit,
            ]);
        }
    }
}

