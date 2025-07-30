<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class PerformanceService
{
    /**
     * Get performance statistics
     */
    public function getStats()
    {
        return [
            'cache_hits' => $this->getCacheHits(),
            'slow_queries' => $this->getSlowQueries(),
            'memory_usage' => $this->getMemoryUsage(),
            'database_connections' => $this->getDatabaseConnections(),
        ];
    }

    /**
     * Optimize the application
     */
    public function optimize()
    {
        $this->clearCaches();
        $this->cacheConfigurations();
        $this->optimizeAutoloader();
        
        return true;
    }

    /**
     * Clear all caches
     */
    public function clearCaches()
    {
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        
        Log::info('All caches cleared');
    }

    /**
     * Cache configurations
     */
    public function cacheConfigurations()
    {
        if (config('performance.optimization.config_caching')) {
            Artisan::call('config:cache');
        }
        
        if (config('performance.optimization.route_caching')) {
            Artisan::call('route:cache');
        }
        
        if (config('performance.optimization.view_caching')) {
            Artisan::call('view:cache');
        }
        
        Log::info('Configurations cached');
    }

    /**
     * Optimize autoloader
     */
    public function optimizeAutoloader()
    {
        exec('composer dump-autoload --optimize');
        Log::info('Autoloader optimized');
    }

    /**
     * Get cache hit statistics
     */
    private function getCacheHits()
    {
        // This would need to be implemented based on your cache driver
        return Cache::get('cache_hits', 0);
    }

    /**
     * Get slow queries count
     */
    private function getSlowQueries()
    {
        return Cache::get('slow_queries_count', 0);
    }

    /**
     * Get memory usage
     */
    private function getMemoryUsage()
    {
        return [
            'current' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true),
            'limit' => ini_get('memory_limit'),
        ];
    }

    /**
     * Get database connections info
     */
    private function getDatabaseConnections()
    {
        try {
            $connections = DB::connection()->getPdo();
            return [
                'active_connections' => $connections ? 1 : 0,
                'max_connections' => config('database.connections.mysql.options.max_connections', 10),
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Monitor query performance
     */
    public function monitorQuery($query, $time)
    {
        $threshold = config('performance.database.slow_query_threshold', 100);
        
        if ($time > $threshold) {
            Log::warning('Slow query detected', [
                'sql' => $query->sql,
                'time' => $time,
                'threshold' => $threshold,
            ]);
            
            Cache::increment('slow_queries_count');
        }
    }
} 