<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        // Log slow requests (>500ms)
        if ($executionTime > 500) {
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => round($executionTime, 2) . 'ms',
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);
        }
        
        // Add execution time to response headers for debugging
        $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
        
        return $response;
    }
} 