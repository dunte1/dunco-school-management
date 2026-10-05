<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class MobileApiOptimization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): BaseResponse
    {
        $response = $next($request);

        // Only optimize JSON responses
        if (!$this->shouldOptimize($request, $response)) {
            return $response;
        }

        // Apply optimizations
        $response = $this->addMobileHeaders($response);
        $response = $this->compressResponse($response);
        $response = $this->addCacheHeaders($request, $response);
        $response = $this->optimizeJsonStructure($response);

        return $response;
    }

    /**
     * Check if response should be optimized
     */
    protected function shouldOptimize(Request $request, $response): bool
    {
        // Check if it's a mobile API request
        $isMobileApi = $request->is('api/mobile/*');
        
        // Check if response is JSON
        $isJson = $response instanceof \Illuminate\Http\JsonResponse || 
                 $response->headers->get('Content-Type', '') === 'application/json';

        return $isMobileApi && $isJson && $response->isSuccessful();
    }

    /**
     * Add mobile-specific headers
     */
    protected function addMobileHeaders($response)
    {
        $response->headers->set('X-Mobile-Optimized', 'true');
        $response->headers->set('X-Response-Time', microtime(true) - LARAVEL_START);
        $response->headers->set('Vary', 'Accept-Encoding, User-Agent');
        
        return $response;
    }

    /**
     * Compress response if supported
     */
    protected function compressResponse($response)
    {
        $content = $response->getContent();
        
        if (strlen($content) > 1024) { // Only compress if > 1KB
            $response->headers->set('Content-Encoding', 'gzip');
            $response->setContent(gzencode($content));
        }

        return $response;
    }

    /**
     * Add appropriate cache headers
     */
    protected function addCacheHeaders(Request $request, $response)
    {
        $method = $request->method();
        $path = $request->path();

        // Cache static/reference data longer
        if ($this->isStaticEndpoint($path)) {
            $response->headers->set('Cache-Control', 'public, max-age=3600'); // 1 hour
        }
        // Cache user-specific data briefly
        elseif ($method === 'GET') {
            $response->headers->set('Cache-Control', 'private, max-age=300'); // 5 minutes
        }
        // Don't cache mutations
        else {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return $response;
    }

    /**
     * Optimize JSON structure for mobile
     */
    protected function optimizeJsonStructure($response)
    {
        $content = $response->getContent();
        $data = json_decode($content, true);

        if (!$data) {
            return $response;
        }

        // Remove null values to reduce payload size
        $data = $this->removeNulls($data);

        // Optimize timestamps
        $data = $this->optimizeTimestamps($data);

        // Add mobile-specific metadata
        $data['_mobile'] = [
            'optimized' => true,
            'timestamp' => time(),
            'version' => '1.0',
        ];

        $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE));
        
        return $response;
    }

    /**
     * Check if endpoint serves static data
     */
    protected function isStaticEndpoint(string $path): bool
    {
        $staticEndpoints = [
            'api/mobile/v1/schools',
            'api/mobile/v1/system/health',
            'api/mobile/v1/translations',
        ];

        foreach ($staticEndpoints as $endpoint) {
            if (str_starts_with($path, $endpoint)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Remove null values recursively
     */
    protected function removeNulls(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            } elseif (is_array($value)) {
                $data[$key] = $this->removeNulls($value);
            }
        }

        return $data;
    }

    /**
     * Optimize timestamp formats for mobile
     */
    protected function optimizeTimestamps(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->optimizeTimestamps($value);
            } elseif (is_string($value) && $this->isTimestamp($key, $value)) {
                // Convert to Unix timestamp for mobile efficiency
                $data[$key . '_unix'] = strtotime($value);
            }
        }

        return $data;
    }

    /**
     * Check if value is a timestamp
     */
    protected function isTimestamp(string $key, string $value): bool
    {
        $timestampKeys = ['created_at', 'updated_at', 'timestamp', 'date', 'time'];
        
        if (!in_array($key, $timestampKeys)) {
            return false;
        }

        return strtotime($value) !== false;
    }
}