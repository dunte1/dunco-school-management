<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        // Add performance headers
        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsed = $endMemory - $startMemory;

        // Add performance headers to response
        $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
        $response->headers->set('X-Memory-Used', number_format($memoryUsed / 1024, 2) . 'KB');

        // Log slow requests
        $slowRequestThreshold = config('performance.monitoring.slow_request_threshold', 500);
        if ($executionTime > $slowRequestThreshold) {
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime . 'ms',
                'memory_used' => number_format($memoryUsed / 1024, 2) . 'KB',
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);
        }

        // Track request statistics
        $this->trackRequestStats($executionTime, $memoryUsed);

        return $response;
    }

    /**
     * Track request statistics
     */
    private function trackRequestStats($executionTime, $memoryUsed)
    {
        // Increment request count
        Cache::increment('total_requests_count');

        // Track average response time
        $avgTime = Cache::get('average_response_time', 0);
        $requestCount = Cache::get('total_requests_count', 1);
        
        $newAvgTime = (($avgTime * ($requestCount - 1)) + $executionTime) / $requestCount;
        Cache::put('average_response_time', $newAvgTime, 3600);

        // Track peak memory usage
        $peakMemory = Cache::get('peak_memory_usage', 0);
        if ($memoryUsed > $peakMemory) {
            Cache::put('peak_memory_usage', $memoryUsed, 3600);
        }

        // Track slow requests count
        $slowRequestThreshold = config('performance.monitoring.slow_request_threshold', 500);
        if ($executionTime > $slowRequestThreshold) {
            Cache::increment('slow_requests_count');
        }
    }
}
