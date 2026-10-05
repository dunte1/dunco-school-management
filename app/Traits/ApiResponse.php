<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ApiResponse
{
    /**
     * Return a success JSON response
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return an error JSON response
     */
    protected function errorResponse(string $message = 'Error', $data = null, int $code = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        // Log error for debugging
        if ($code >= 500) {
            Log::error('API Error: ' . $message, [
                'code' => $code,
                'data' => $data,
                'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
            ]);
        }

        return response()->json($response, $code);
    }

    /**
     * Return a validation error response
     */
    protected function validationErrorResponse(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toISOString(),
        ], 422);
    }

    /**
     * Return a not found response
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, null, 404);
    }

    /**
     * Return an unauthorized response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, null, 401);
    }

    /**
     * Return a forbidden response
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, null, 403);
    }

    /**
     * Return a server error response
     */
    protected function serverErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return $this->errorResponse($message, null, 500);
    }

    /**
     * Return a paginated response
     */
    protected function paginatedResponse($data, string $message = 'Data retrieved successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Validate request data
     */
    protected function validateRequest(Request $request, array $rules, array $messages = []): array
    {
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Handle exceptions gracefully
     */
    protected function handleException(\Exception $e, string $context = 'API'): JsonResponse
    {
        $message = $e->getMessage();
        $code = 500;

        // Handle specific exception types
        if ($e instanceof ValidationException) {
            return $this->validationErrorResponse($e->errors(), 'Validation failed');
        }

        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return $this->unauthorizedResponse('Authentication required');
        }

        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return $this->forbiddenResponse('Access denied');
        }

        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->notFoundResponse('Resource not found');
        }

        // Log the exception
        Log::error("{$context} Exception: " . $message, [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Return appropriate error response
        if (config('app.debug')) {
            return $this->errorResponse($message, [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], $code);
        }

        return $this->serverErrorResponse('An error occurred while processing your request');
    }

    /**
     * Check if user has required role
     */
    protected function checkRole($user, array $roles): bool
    {
        if (!$user) {
            return false;
        }

        $userRoles = $user->roles->pluck('name')->toArray();
        return !empty(array_intersect($roles, $userRoles));
    }

    /**
     * Check if user has required permission
     */
    protected function checkPermission($user, string $permission): bool
    {
        if (!$user) {
            return false;
        }

        return $user->can($permission);
    }

    /**
     * Get user school ID
     */
    protected function getUserSchoolId($user): int
    {
        return $user->school_id ?? 1;
    }

    /**
     * Format API response data
     */
    protected function formatResponseData($data, array $fields = []): array
    {
        if (empty($fields)) {
            return $data;
        }

        if (is_array($data)) {
            return array_intersect_key($data, array_flip($fields));
        }

        if (is_object($data)) {
            $result = [];
            foreach ($fields as $field) {
                if (isset($data->$field)) {
                    $result[$field] = $data->$field;
                }
            }
            return $result;
        }

        return $data;
    }

    /**
     * Sanitize input data
     */
    protected function sanitizeInput(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = trim(strip_tags($value));
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeInput($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Generate API response with caching
     */
    protected function cachedResponse(string $key, callable $callback, int $ttl = 300): JsonResponse
    {
        $cacheKey = "api_response_{$key}";
        
        $data = cache()->remember($cacheKey, $ttl, $callback);
        
        return $this->successResponse($data);
    }

    /**
     * Clear API response cache
     */
    protected function clearCache(string $key): void
    {
        $cacheKey = "api_response_{$key}";
        cache()->forget($cacheKey);
    }

    /**
     * Rate limit check
     */
    protected function checkRateLimit(Request $request, int $maxAttempts = 60, int $decayMinutes = 1): bool
    {
        $key = $request->ip() . ':' . $request->user()?->id;
        
        if (cache()->has("rate_limit_{$key}")) {
            $attempts = cache()->get("rate_limit_{$key}");
            if ($attempts >= $maxAttempts) {
                return false;
            }
            cache()->increment("rate_limit_{$key}");
        } else {
            cache()->put("rate_limit_{$key}", 1, now()->addMinutes($decayMinutes));
        }

        return true;
    }

    /**
     * Log API activity
     */
    protected function logActivity(string $action, array $data = []): void
    {
        Log::info("API Activity: {$action}", [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Generate unique request ID
     */
    protected function generateRequestId(): string
    {
        return 'req_' . uniqid() . '_' . time();
    }

    /**
     * Add request ID to response
     */
    protected function addRequestId(JsonResponse $response, string $requestId = null): JsonResponse
    {
        $requestId = $requestId ?? $this->generateRequestId();
        
        $data = $response->getData(true);
        $data['request_id'] = $requestId;
        
        return response()->json($data, $response->getStatusCode());
    }
}