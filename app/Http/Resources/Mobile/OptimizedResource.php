<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class OptimizedResource extends JsonResource
{
    /**
     * Transform the resource into an array optimized for mobile.
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        
        // Apply mobile-specific optimizations
        return $this->optimizeForMobile($data);
    }

    /**
     * Optimize data structure for mobile consumption
     */
    protected function optimizeForMobile(array $data): array
    {
        // Remove unnecessary nested objects
        $data = $this->flattenUnnecessaryNesting($data);
        
        // Optimize image URLs for mobile
        $data = $this->optimizeImageUrls($data);
        
        // Compress text fields
        $data = $this->optimizeTextFields($data);
        
        // Add mobile-specific computed fields
        $data = $this->addMobileFields($data);
        
        return $data;
    }

    /**
     * Flatten unnecessary nesting in response
     */
    protected function flattenUnnecessaryNesting(array $data): array
    {
        // Example: Flatten user.profile.avatar to user_avatar
        if (isset($data['user']['profile']['avatar'])) {
            $data['user_avatar'] = $data['user']['profile']['avatar'];
            unset($data['user']['profile']['avatar']);
            
            if (empty($data['user']['profile'])) {
                unset($data['user']['profile']);
            }
        }

        return $data;
    }

    /**
     * Optimize image URLs for different screen densities
     */
    protected function optimizeImageUrls(array $data): array
    {
        $imageFields = ['avatar', 'profile_photo', 'image', 'photo', 'thumbnail'];
        
        foreach ($imageFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field . '_variants'] = [
                    'small' => $this->getImageVariant($data[$field], 'small'),
                    'medium' => $this->getImageVariant($data[$field], 'medium'),
                    'large' => $data[$field], // Original
                ];
                
                // Set mobile-optimized default
                $data[$field] = $data[$field . '_variants']['medium'];
            }
        }

        return $data;
    }

    /**
     * Optimize text fields for mobile display
     */
    protected function optimizeTextFields(array $data): array
    {
        if (isset($data['description']) && strlen($data['description']) > 200) {
            $data['description_short'] = substr($data['description'], 0, 200) . '...';
        }

        if (isset($data['content']) && strlen($data['content']) > 500) {
            $data['content_preview'] = substr(strip_tags($data['content']), 0, 500) . '...';
        }

        return $data;
    }

    /**
     * Add mobile-specific computed fields
     */
    protected function addMobileFields(array $data): array
    {
        // Add relative time for mobile display
        if (isset($data['created_at'])) {
            $data['created_at_relative'] = $this->getRelativeTime($data['created_at']);
        }

        if (isset($data['updated_at'])) {
            $data['updated_at_relative'] = $this->getRelativeTime($data['updated_at']);
        }

        return $data;
    }

    /**
     * Get image variant URL
     */
    protected function getImageVariant(string $originalUrl, string $size): string
    {
        $dimensions = [
            'small' => '150x150',
            'medium' => '300x300',
            'large' => '600x600',
        ];

        $dimension = $dimensions[$size] ?? $dimensions['medium'];
        
        // If using a service like Cloudinary or similar
        if (str_contains($originalUrl, 'cloudinary.com')) {
            return str_replace('/upload/', "/upload/c_fill,w_{$dimension}/", $originalUrl);
        }

        // For local images, you might generate variants on-the-fly
        return $originalUrl . "?size={$dimension}";
    }

    /**
     * Get relative time string
     */
    protected function getRelativeTime(string $timestamp): string
    {
        $time = strtotime($timestamp);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . 'm ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . 'h ago';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return $days . 'd ago';
        } else {
            return date('M j', $time);
        }
    }

    /**
     * Custom response wrapper
     */
    public function with($request)
    {
        return [
            'meta' => [
                'mobile_optimized' => true,
                'response_time' => round((microtime(true) - LARAVEL_START) * 1000, 2) . 'ms',
                'cache_key' => $this->generateCacheKey($request),
            ],
        ];
    }

    /**
     * Generate cache key for client-side caching
     */
    protected function generateCacheKey($request): string
    {
        $user = $request->user();
        $userId = $user ? $user->id : 'guest';
        $path = $request->path();
        
        return md5($userId . '-' . $path . '-' . $request->getQueryString());
    }
}