<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

if (!function_exists('module_path')) {
    /**
     * Get the path to a module directory
     */
    function module_path($module, $path = '')
    {
        $modulePath = base_path('Modules/' . $module);
        
        if ($path) {
            return $modulePath . '/' . $path;
        }
        
        return $modulePath;
    }
}

class NavigationHelper
{
    public static function canAccessModule($module)
    {
        $user = Auth::user();
        if (!$user) return false;
        
        // If user is system admin, allow access to all modules
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        // Check if module is enabled in modules_statuses.json
        $moduleEnabled = false;
        $statusFile = base_path('modules_statuses.json');
        if (file_exists($statusFile)) {
            $statuses = json_decode(file_get_contents($statusFile), true);
            // Check both lowercase and capitalized versions
            $moduleKey = ucfirst($module);
            if (isset($statuses[$moduleKey]) && $statuses[$moduleKey] === true) {
                $moduleEnabled = true;
            } elseif (isset($statuses[$module]) && $statuses[$module] === true) {
                $moduleEnabled = true;
            }
        }

        // Define functional modules (only the ones that actually exist)
        $functionalModules = [
            'core', 'academic', 'examination', 'finance', 'hr', 'library', 
            'hostel', 'transport', 'timetable', 'attendance', 'communication', 
            'portal', 'document', 'notification', 'settings', 'api', 'chatbot', 'nursing'
        ];

        // If module is not in functional modules, return false
        if (!in_array($module, $functionalModules)) {
            return false;
        }

        // If module is enabled in modules_statuses.json, check permissions
        if ($moduleEnabled) {
            // For non-admin users, check specific permissions
            $modulePermissions = [
                'core' => ['schools.view', 'users.view', 'roles.view', 'permissions.view'],
                'academic' => ['academic.view', 'academic.students.view', 'academic.classes.view', 'academic.subjects.view'],
                'examination' => ['examination.view', 'examination.teacher.exams', 'examination.teacher.grade'],
                'finance' => ['finance.view', 'finance.fees.view', 'finance.billing.view', 'finance.payments.view'],
                'hr' => ['hr.view', 'hr.staff.view', 'hr.leave.view', 'hr.payroll.view', 'hr.contract.view', 'hr.departments.view'],
                'library' => ['library.view', 'library.books.view', 'library.categories.view', 'library.authors.view', 'library.publishers.view', 'library.members.view', 'library.borrows.view', 'library.reports.view'],
                'hostel' => ['hostel.view', 'hostel.allocations.view', 'hostel.fees.view', 'hostel.issues.view', 'hostel.leave.view', 'hostel.visitors.view', 'hostel.announcements.view', 'hostel.reports.view'],
                'transport' => ['transport.view', 'transport.vehicles.view', 'transport.routes.view', 'transport.drivers.view', 'transport.trips.view'],
                'timetable' => ['timetable.view', 'timetable.schedules.view', 'timetable.teacher_availabilities.view', 'timetable.rooms.view', 'timetable.room_allocations.view'],
                'attendance' => ['attendance.view', 'attendance.mark.view', 'attendance.reports.view', 'attendance.settings.view'],
                'communication' => ['communication.view', 'communication.inbox.view', 'communication.compose.view', 'communication.announcements.view'],
                'portal' => ['portal.view', 'portal.student.view', 'portal.parent.view'],
                'document' => ['document.view', 'document.upload.view', 'document.manage.view'],
                'notification' => ['notification.view', 'notification.manage.view'],
                'settings' => ['settings.view', 'settings.global.view', 'settings.per_school.view'],
                'api' => ['api.view', 'api.manage.view'],
                'chatbot' => ['chatbot.view', 'chatbot.manage.view'],
                'nursing' => ['nursing.dashboard.view', 'nursing.placements.view', 'nursing.logbooks.view', 'nursing.skills.view'],
            ];

            if (!isset($modulePermissions[$module])) {
                return false;
            }

            $userPermissions = self::getCachedUserPermissions($user->id);
            
            // Check if user has ANY of the required permissions for this module
            foreach ($modulePermissions[$module] as $permission) {
                if (in_array($permission, $userPermissions)) {
                    return true;
                }
            }
        }
        
        return false;
    }

    public static function getUserModules()
    {
        $user = Auth::user();
        if (!$user) return [];

        return Cache::remember("nav:user:{$user->id}:modules", 300, function () use ($user) {
            $allModules = [
                'core','academic','examination','finance','hr','library','hostel','transport',
                'timetable','attendance','communication','portal','document','notification',
                'settings','api','chatbot','nursing'
            ];

            // Admin gets everything
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return $allModules;
            }

            // Role-based default modules
            $roleModules = [
                'teacher'           => ['core','academic','examination','attendance','timetable','library','communication','notification','portal'],
                'student'           => ['core','academic','examination','library','hostel','transport','attendance','portal','notification'],
                'parent'            => ['core','academic','examination','library','hostel','finance','portal','notification'],
                'finance_manager'   => ['core','finance','notification'],
                'hr_manager'        => ['core','hr','attendance','notification'],
                'librarian'         => ['core','library','notification'],
                'timetable_manager' => ['core','timetable','academic','notification'],
                'hostel_manager'    => ['core','hostel','notification'],
            ];

            $userRoles = $user->roles->pluck('name')->toArray();
            $visible = [];

            foreach ($userRoles as $roleName) {
                if (isset($roleModules[$roleName])) {
                    $visible = array_merge($visible, $roleModules[$roleName]);
                }
            }

            // Fallback: check permissions
            if (empty($visible)) {
                foreach ($allModules as $module) {
                    if (self::canAccessModule($module)) {
                        $visible[] = $module;
                    }
                }
            }

            // Always show core for authenticated users
            if (!in_array('core', $visible)) {
                $visible[] = 'core';
            }

            return array_values(array_unique($visible));
        });
    }

    public static function hasPermission($permission)
    {
        $user = Auth::user();
        if (!$user) return false;
        // If user is system admin, allow access to all permissions
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }
        $permissions = self::getCachedUserPermissions($user->id);
        return in_array($permission, $permissions, true);
    }

    public static function hasAnyPermission($permissions)
    {
        $user = Auth::user();
        if (!$user) return false;
        // If user is system admin, allow access to all permissions
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }
        
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        
        $userPermissions = self::getCachedUserPermissions($user->id);
        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions, true)) {
                return true;
            }
        }
        
        return false;
    }

    public static function hasAllPermissions($permissions)
    {
        $user = Auth::user();
        if (!$user) return false;
        // If user is system admin, allow access to all permissions
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }
        
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        
        $userPermissions = self::getCachedUserPermissions($user->id);
        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions, true)) {
                return false;
            }
        }
        
        return true;
    }

    public static function hasRole($role)
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->hasRole($role);
    }

    public static function hasAnyRole($roles)
    {
        $user = Auth::user();
        if (!$user) return false;
        
        if (is_string($roles)) {
            $roles = [$roles];
        }
        
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }
        
        return false;
    }

    protected static function getCachedUserPermissions(int $userId): array
    {
        return Cache::remember("nav:user:{$userId}:perms", 300, function () use ($userId) {
            $user = Auth::user();
            if (!$user) {
                return [];
            }
            if (method_exists($user, 'getAllPermissionNames')) {
                try {
                    return $user->getAllPermissionNames()->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            }
            if (method_exists($user, 'getAllPermissions')) {
                try {
                    return $user->getAllPermissions()->pluck('name')->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            }
            return [];
        });
    }
} 