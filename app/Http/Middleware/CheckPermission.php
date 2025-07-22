<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Debug output
        \Log::info('User ID: ' . $user->id);
        \Log::info('User roles: ', $user->roles->pluck('name')->toArray());
        \Log::info('Checking permission: ' . $permission);
        \Log::info('User permissions: ', $user->getAllPermissions()->pluck('name')->toArray());

        // Check if user has the required permission through their roles
        $hasPermission = $user->roles()->whereHas('permissions', function($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();

        if (!$hasPermission) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Insufficient permissions.'], 403);
            }
            
            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
} 