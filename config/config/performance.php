<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains performance-related configurations for the application.
    |
    */

    'cache' => [
        'enabled' => env('PERFORMANCE_CACHE_ENABLED', true),
        'ttl' => env('PERFORMANCE_CACHE_TTL', 3600), // 1 hour
        'prefix' => env('PERFORMANCE_CACHE_PREFIX', 'duncoschool_'),
        'driver' => env('PERFORMANCE_CACHE_DRIVER', 'file'), // file, redis, memcached
    ],

    'database' => [
        'query_timeout' => env('DB_QUERY_TIMEOUT', 30), // seconds
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 100), // milliseconds
        'connection_pool_size' => env('DB_CONNECTION_POOL_SIZE', 10),
        'enable_query_log' => env('DB_ENABLE_QUERY_LOG', false),
        'optimize_tables' => env('DB_OPTIMIZE_TABLES', true),
        'index_hints' => env('DB_INDEX_HINTS', false),
    ],

    'monitoring' => [
        'enabled' => env('PERFORMANCE_MONITORING_ENABLED', true),
        'slow_request_threshold' => env('PERFORMANCE_SLOW_REQUEST_THRESHOLD', 500), // milliseconds
        'log_slow_queries' => env('PERFORMANCE_LOG_SLOW_QUERIES', true),
        'log_slow_requests' => env('PERFORMANCE_LOG_SLOW_REQUESTS', true),
        'track_memory_usage' => env('PERFORMANCE_TRACK_MEMORY_USAGE', true),
        'track_cache_hits' => env('PERFORMANCE_TRACK_CACHE_HITS', true),
    ],

    'optimization' => [
        'eager_loading_required' => env('PERFORMANCE_EAGER_LOADING_REQUIRED', true),
        'query_logging' => env('PERFORMANCE_QUERY_LOGGING', false),
        'route_caching' => env('PERFORMANCE_ROUTE_CACHING', true),
        'config_caching' => env('PERFORMANCE_CONFIG_CACHING', true),
        'view_caching' => env('PERFORMANCE_VIEW_CACHING', true),
        'autoloader_optimization' => env('PERFORMANCE_AUTOLOADER_OPTIMIZATION', true),
        'database_optimization' => env('PERFORMANCE_DATABASE_OPTIMIZATION', true),
    ],

    'caching' => [
        'dashboard_stats' => [
            'enabled' => env('CACHE_DASHBOARD_STATS', true),
            'ttl' => env('CACHE_DASHBOARD_STATS_TTL', 1800), // 30 minutes
        ],
        'user_data' => [
            'enabled' => env('CACHE_USER_DATA', true),
            'ttl' => env('CACHE_USER_DATA_TTL', 3600), // 1 hour
        ],
        'student_data' => [
            'enabled' => env('CACHE_STUDENT_DATA', true),
            'ttl' => env('CACHE_STUDENT_DATA_TTL', 1800), // 30 minutes
        ],
        'school_stats' => [
            'enabled' => env('CACHE_SCHOOL_STATS', true),
            'ttl' => env('CACHE_SCHOOL_STATS_TTL', 3600), // 1 hour
        ],
    ],

    'memory' => [
        'limit' => env('MEMORY_LIMIT', '256M'),
        'track_usage' => env('TRACK_MEMORY_USAGE', true),
        'cleanup_threshold' => env('MEMORY_CLEANUP_THRESHOLD', 80), // percentage
    ],

    'query_optimization' => [
        'prevent_n_plus_one' => env('PREVENT_N_PLUS_ONE', true),
        'use_index_hints' => env('USE_INDEX_HINTS', false),
        'chunk_processing' => env('CHUNK_PROCESSING', true),
        'chunk_size' => env('CHUNK_SIZE', 1000),
        'select_optimization' => env('SELECT_OPTIMIZATION', true),
    ],

    'maintenance' => [
        'auto_cleanup' => env('AUTO_CLEANUP', true),
        'cleanup_interval' => env('CLEANUP_INTERVAL', 24), // hours
        'log_retention_days' => env('LOG_RETENTION_DAYS', 7),
        'cache_retention_hours' => env('CACHE_RETENTION_HOURS', 24),
    ],

]; 