<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SchoolDataIsolation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return $next($request);
        }

        // System admin (school_id = null) can access all schools
        if ($user->school_id === null) {
            // Get the requested school ID from various sources
            $requestedSchoolId = $this->getRequestedSchoolId($request);
            
            // For system admin, if no specific school is requested, allow access to all
            if (!$requestedSchoolId) {
                $request->attributes->set('current_school_id', null);
                $request->merge(['school_id' => null]);
                return $next($request);
            }
            
            // System admin can access any school
            $request->attributes->set('current_school_id', $requestedSchoolId);
            $request->merge(['school_id' => $requestedSchoolId]);
            return $next($request);
        }

        // Super admin can access all schools
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Get the requested school ID from various sources
        $requestedSchoolId = $this->getRequestedSchoolId($request);
        
        // If no school ID is requested, use user's primary school
        if (!$requestedSchoolId) {
            // Cast to int to ensure consistent type for comparisons
            $requestedSchoolId = (int) $user->school_id;
        }

        // Check if user has access to the requested school
        if (!$this->userHasAccessToSchool($user, $requestedSchoolId)) {
            abort(403, 'You do not have access to this school\'s data.');
        }

        // Set the school context for the request
        $request->attributes->set('current_school_id', $requestedSchoolId);
        
        // Add school context to the request for use in controllers
        $request->merge(['school_id' => $requestedSchoolId]);

        return $next($request);
    }

    /**
     * Get the requested school ID from various sources
     */
    private function getRequestedSchoolId(Request $request): ?int
    {
        // Check route parameters
        if ($request->route('school_id')) {
            return (int) $request->route('school_id');
        }

        // Check query parameters
        if ($request->query('school_id')) {
            return (int) $request->query('school_id');
        }

        // Check request body
        if ($request->input('school_id')) {
            return (int) $request->input('school_id');
        }

        // Check headers
        if ($request->header('X-School-ID')) {
            return (int) $request->header('X-School-ID');
        }

        return null;
    }

    /**
     * Check if user has access to the specified school
     */
    private function userHasAccessToSchool($user, ?int $schoolId): bool
    {
        // System admin (school_id = null) can access all schools
        if ($user->school_id === null) {
            return true;
        }

        // If no school ID is provided, user can only access their own school
        if ($schoolId === null) {
            return true; // Let the application handle this case
        }

        // User's primary school
        // Ensure both IDs are compared as integers to prevent strict type mismatches
        if ((int) $user->school_id === (int) $schoolId) {
            return true;
        }

        // Check if user has roles in the requested school
        $userRolesInSchool = $user->rolesForSchool($schoolId);
        if ($userRolesInSchool->isNotEmpty()) {
            return true;
        }

        // Check if user is a super admin
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return false;
    }
}
