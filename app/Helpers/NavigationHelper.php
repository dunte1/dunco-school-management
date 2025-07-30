<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

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

        $modulePermissions = [
            'core' => ['schools.view', 'users.view', 'roles.view', 'permissions.view', 'audit.view'],
            'academic' => ['academic.view', 'academic.students.view', 'academic.classes.view', 'academic.subjects.view'],
            'examination' => ['examination.view', 'examination.teacher.exams', 'examination.teacher.grade'],
            'finance' => ['finance.view', 'finance.fees.view', 'finance.billing.view', 'finance.payments.view'],
            'hr' => ['hr.view', 'hr.staff.view', 'hr.leave.view', 'hr.payroll.view', 'hr.contract.view', 'hr.departments.view'],
            'library' => ['library.view', 'library.books.view', 'library.categories.view', 'library.authors.view', 'library.publishers.view', 'library.members.view', 'library.borrows.view', 'library.reports.view'],
            'hostel' => ['hostel.view', 'hostel.allocations.view', 'hostel.fees.view', 'hostel.issues.view', 'hostel.leave.view', 'hostel.visitors.view', 'hostel.announcements.view', 'hostel.reports.view'],
            'transport' => ['transport.view', 'transport.vehicles.view', 'transport.routes.view', 'transport.drivers.view', 'transport.trips.view'],
            'timetable' => ['timetable.view', 'timetable.schedules.view', 'timetable.teacher_availabilities.view', 'timetable.rooms.view', 'timetable.room_allocations.view'],
            'attendance' => ['attendance.view', 'attendance.mark.view', 'attendance.reports.view', 'attendance.settings.view', 'attendance.biometric_logs.view', 'attendance.qr_logs.view', 'attendance.face_logs.view', 'attendance.acknowledgment_logs.view'],
            'communication' => ['communication.view', 'communication.inbox.view', 'communication.compose.view', 'communication.announcements.view'],
            'portal' => ['portal.view', 'portal.student.view', 'portal.parent.view'],
            'document' => ['document.view', 'document.upload.view', 'document.manage.view'],
            'notification' => ['notification.view', 'notification.manage.view'],
            'settings' => ['settings.view', 'settings.global.view', 'settings.per_school.view'],
            'api' => ['api.view', 'api.manage.view'],
            'chatbot' => ['chatbot.view', 'chatbot.manage.view'],
        ];

        if (!isset($modulePermissions[$module])) {
            return false;
        }

        $userPermissions = $user->getAllPermissionNames()->toArray();
        
        // Check if user has ANY of the required permissions for this module
        foreach ($modulePermissions[$module] as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }
        
        return false;
    }

    public static function getUserModules()
    {
        $user = Auth::user();
        if (!$user) return [];

        $availableModules = [
            'core', 'academic', 'examination', 'finance', 'hr', 'library', 
            'hostel', 'transport', 'timetable', 'attendance', 'communication', 
            'portal', 'document', 'notification', 'settings', 'api', 'chatbot'
        ];

        $userModules = [];
        foreach ($availableModules as $module) {
            if (self::canAccessModule($module)) {
                $userModules[] = $module;
            }
        }

        return $userModules;
    }

    public static function hasPermission($permission)
    {
        $user = Auth::user();
        if (!$user) return false;
        // If user is system admin, allow access to all permissions
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }
        return $user->hasPermission($permission);
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
        
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
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
        
        foreach ($permissions as $permission) {
            if (!$user->hasPermission($permission)) {
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
} 