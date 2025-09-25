<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class LogUserActions
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
        $response = $next($request);

        // Log authentication events
        if ($request->is('login') && $request->method() === 'POST') {
            if (Auth::check()) {
                AuditLog::log(
                    'auth.login',
                    'User logged in successfully',
                    null,
                    ['email' => Auth::user()->email]
                );
            }
        }

        if ($request->is('logout') && $request->method() === 'POST') {
            if (Auth::check()) {
                AuditLog::log(
                    'auth.logout',
                    'User logged out',
                    ['email' => Auth::user()->email],
                    null
                );
            }
        }

        return $response;
    }
} 