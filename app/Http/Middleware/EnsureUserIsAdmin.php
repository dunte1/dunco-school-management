<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Roles treated as administrative access.
     *
     * @var list<string>
     */
    protected array $adminRoles = ['admin', 'super_admin', 'system_administrator'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        if (!method_exists($user, 'hasAnyRole') || !$user->hasAnyRole($this->adminRoles)) {
            abort(403, 'Administrator access required.');
        }

        return $next($request);
    }
}
