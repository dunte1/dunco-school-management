<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\CacheManager;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure cache optimization
        if (config('performance.cache.enabled')) {
            $this->configureCacheOptimization();
        }
    }

    /**
     * Configure cache optimization settings
     */
    private function configureCacheOptimization(): void
    {
        // Set default TTL for cache
        $defaultTtl = config('performance.cache.ttl', 3600);
        
        // Configure cache tags if using Redis
        if (config('cache.default') === 'redis') {
            Cache::tags(['app', 'performance'])->flush();
        }
    }
}

