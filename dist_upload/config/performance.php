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
    ],

    'database' => [
        'query_timeout' => env('DB_QUERY_TIMEOUT', 30), // seconds
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 100), // milliseconds
        'connection_pool_size' => env('DB_CONNECTION_POOL_SIZE', 10),
    ],

    'monitoring' => [
        'enabled' => env('PERFORMANCE_MONITORING_ENABLED', true),
        'slow_request_threshold' => env('PERFORMANCE_SLOW_REQUEST_THRESHOLD', 500), // milliseconds
        'log_slow_queries' => env('PERFORMANCE_LOG_SLOW_QUERIES', true),
        'log_slow_requests' => env('PERFORMANCE_LOG_SLOW_REQUESTS', true),
    ],

    'optimization' => [
        'eager_loading_required' => env('PERFORMANCE_EAGER_LOADING_REQUIRED', true),
        'query_logging' => env('PERFORMANCE_QUERY_LOGGING', false),
        'route_caching' => env('PERFORMANCE_ROUTE_CACHING', true),
        'config_caching' => env('PERFORMANCE_CONFIG_CACHING', true),
        'view_caching' => env('PERFORMANCE_VIEW_CACHING', true),
    ],

]; 