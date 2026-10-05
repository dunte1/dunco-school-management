<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

abstract class BaseMobileController extends Controller
{
    use ApiResponse;

    /**
     * Check if user has required role
     */
    protected function requireRole(Request $request, string $role): bool
    {
        $user = $request->user();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        if (!in_array($role, $userRoles)) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if user has any of the required roles
     */
    protected function requireAnyRole(Request $request, array $roles): bool
    {
        $user = $request->user();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get user's primary role
     */
    protected function getUserRole(Request $request): ?string
    {
        $user = $request->user();
        return $user->roles->first()?->name;
    }

    /**
     * Check if user is a student
     */
    protected function isStudent(Request $request): bool
    {
        return $this->requireRole($request, 'student');
    }

    /**
     * Check if user is a parent
     */
    protected function isParent(Request $request): bool
    {
        return $this->requireRole($request, 'parent');
    }

    /**
     * Check if user is an admin
     */
    protected function isAdmin(Request $request): bool
    {
        return $this->requireAnyRole($request, ['admin', 'super_admin']);
    }

    /**
     * Check if user is staff
     */
    protected function isStaff(Request $request): bool
    {
        return $this->requireAnyRole($request, ['teacher', 'academic_admin', 'finance_manager', 'librarian']);
    }
}
