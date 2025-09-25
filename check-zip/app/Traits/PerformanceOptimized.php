<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

trait PerformanceOptimized
{
    /**
     * Cache query results with automatic key generation
     */
    public function scopeCached(Builder $query, $ttl = 3600, $key = null)
    {
        $cacheKey = $key ?? $this->generateCacheKey($query);
        
        return Cache::remember($cacheKey, $ttl, function () use ($query) {
            return $query->get();
        });
    }

    /**
     * Generate a unique cache key based on the query
     */
    protected function generateCacheKey($query)
    {
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        
        return 'query_' . md5($sql . serialize($bindings));
    }

    /**
     * Scope to prevent N+1 queries by forcing eager loading
     */
    public function scopeWithRelations(Builder $query, array $relations = [])
    {
        $defaultRelations = $this->getDefaultRelations();
        $allRelations = array_merge($defaultRelations, $relations);
        
        return $query->with($allRelations);
    }

    /**
     * Get default relations that should always be loaded
     */
    protected function getDefaultRelations()
    {
        return [];
    }

    /**
     * Scope to limit results for better performance
     */
    public function scopeOptimized(Builder $query, $limit = 100)
    {
        return $query->limit($limit);
    }

    /**
     * Clear model cache
     */
    public static function clearCache()
    {
        $model = new static();
        $table = $model->getTable();
        
        Cache::forget("table_{$table}_cache");
    }
} 