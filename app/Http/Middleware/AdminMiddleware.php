<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Block access if user not logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Allow only admins or super admins
        if (!$user->hasRole(['admin', 'super_admin'])) {
            abort(403, 'You do not have administrator access.');
        }

        return $next($request);
    }
}
