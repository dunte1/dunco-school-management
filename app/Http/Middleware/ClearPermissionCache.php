<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ClearPermissionCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Clear any cached permissions for the current user
        if (Auth::check()) {
            $user = Auth::user();
            $cacheKey = "user_permissions_{$user->id}";
            Cache::forget($cacheKey);
            
            // Also clear any cached roles
            $roleCacheKey = "user_roles_{$user->id}";
            Cache::forget($roleCacheKey);
        }

        return $next($request);
    }
} 