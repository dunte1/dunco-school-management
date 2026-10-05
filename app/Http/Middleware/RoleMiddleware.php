<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage in routes: middleware(['role:super_admin']) or ['role:admin,teacher']
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            // Not authenticated
            return redirect()->guest(route('login'));
        }

        // Normalize roles
        if (count($roles) === 1 && str_contains($roles[0], '|')) {
            $roles = explode('|', $roles[0]);
        }
        $roles = array_filter(array_map('trim', $roles));
        if (empty($roles)) {
            // No role specified; allow
            return $next($request);
        }

        // Try primary role first
        $primary = $user->primaryRole ?? $user->loadMissing('primaryRole')->primaryRole;
        if ($primary && in_array($primary->name, $roles, true)) {
            return $next($request);
        }

        // Fallback: check any assigned role names
        $userRoles = $user->relationLoaded('roles') ? $user->roles : $user->loadMissing('roles')->roles;
        $userRoleNames = $userRoles->pluck('name')->all();
        foreach ($roles as $role) {
            if (in_array($role, $userRoleNames, true)) {
                return $next($request);
            }
        }

        abort(403, 'This action is unauthorized.');
    }
}
