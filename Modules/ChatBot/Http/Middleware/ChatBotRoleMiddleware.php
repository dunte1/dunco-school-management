<?php

namespace Modules\ChatBot\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatBotRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If specific role is required, check it
        if ($role) {
            // Check if user has the required role
            if ($user->role !== $role && !$user->hasPermission('chatbot.admin')) {
                abort(403, 'Unauthorized access to chatbot');
            }
        }

        // Check if user has chatbot access permission
        if (!$user->hasPermission('chatbot.view') && !$user->hasPermission('chatbot.admin')) {
            abort(403, 'You do not have permission to access the chatbot');
        }

        return $next($request);
    }
}