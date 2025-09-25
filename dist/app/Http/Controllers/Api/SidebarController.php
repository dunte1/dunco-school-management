<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\NavigationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SidebarController extends Controller
{
    /**
     * Get current user's sidebar data with permissions
     */
    public function getSidebarData()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get user permissions
        $userPermissions = $user->getAllPermissionNames()->toArray();
        
        // Get accessible modules
        $accessibleModules = NavigationHelper::getUserModules();
        
        // Define sidebar structure with permission checks
        $sidebarData = [
            'modules' => [
                'core' => [
                    'title' => 'Core Modules',
                    'icon' => 'fas fa-cogs',
                    'accessible' => in_array('core', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Schools',
                            'route' => 'core.schools.index',
                            'icon' => 'fas fa-school',
                            'accessible' => in_array('schools.view', $userPermissions)
                        ],
                        [
                            'name' => 'Users',
                            'route' => 'core.users.index',
                            'icon' => 'fas fa-users',
                            'accessible' => in_array('users.view', $userPermissions)
                        ],
                        [
                            'name' => 'Roles',
                            'route' => 'core.roles.index',
                            'icon' => 'fas fa-user-shield',
                            'accessible' => in_array('roles.view', $userPermissions)
                        ],
                        [
                            'name' => 'Permissions',
                            'route' => 'core.permissions.index',
                            'icon' => 'fas fa-key',
                            'accessible' => in_array('permissions.view', $userPermissions)
                        ],
                        [
                            'name' => 'Audit Logs',
                            'route' => 'core.audit_logs.index',
                            'icon' => 'fas fa-history',
                            'accessible' => in_array('audit.view', $userPermissions)
                        ]
                    ]
                ],
                'academic' => [
                    'title' => 'Academic',
                    'icon' => 'fas fa-graduation-cap',
                    'accessible' => in_array('academic', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'academic.dashboard',
                            'icon' => 'fas fa-chalkboard',
                            'accessible' => in_array('academic.view', $userPermissions)
                        ],
                        [
                            'name' => 'Students',
                            'route' => 'academic.students.index',
                            'icon' => 'fas fa-user-graduate',
                            'accessible' => in_array('academic.students.view', $userPermissions)
                        ],
                        [
                            'name' => 'Classes',
                            'route' => 'academic.classes.index',
                            'icon' => 'fas fa-door-open',
                            'accessible' => in_array('academic.classes.view', $userPermissions)
                        ],
                        [
                            'name' => 'Subjects',
                            'route' => 'academic.subjects.index',
                            'icon' => 'fas fa-book',
                            'accessible' => in_array('academic.subjects.view', $userPermissions)
                        ],
                        [
                            'name' => 'Grading',
                            'route' => 'academic.grading.index',
                            'icon' => 'fas fa-clipboard-check',
                            'accessible' => in_array('academic.grading.view', $userPermissions)
                        ],
                        [
                            'name' => 'Reports',
                            'route' => 'academic.reports.index',
                            'icon' => 'fas fa-chart-bar',
                            'accessible' => in_array('academic.reports.view', $userPermissions)
                        ]
                    ]
                ],
                'examination' => [
                    'title' => 'Examination',
                    'icon' => 'fas fa-file-alt',
                    'accessible' => in_array('examination', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'examination.dashboard',
                            'icon' => 'fas fa-chart-line',
                            'accessible' => in_array('examination.view', $userPermissions)
                        ],
                        [
                            'name' => 'Exams',
                            'route' => 'examination.exams.index',
                            'icon' => 'fas fa-file-alt',
                            'accessible' => in_array('examination.exams.view', $userPermissions)
                        ],
                        [
                            'name' => 'Categories',
                            'route' => 'examination.categories.index',
                            'icon' => 'fas fa-folder-open',
                            'accessible' => in_array('examination.categories.view', $userPermissions)
                        ],
                        [
                            'name' => 'Schedules',
                            'route' => 'examination.schedules.index',
                            'icon' => 'fas fa-calendar-alt',
                            'accessible' => in_array('examination.schedules.view', $userPermissions)
                        ],
                        [
                            'name' => 'Results',
                            'route' => 'examination.results.index',
                            'icon' => 'fas fa-chart-bar',
                            'accessible' => in_array('examination.results.view', $userPermissions)
                        ]
                    ]
                ],
                'finance' => [
                    'title' => 'Finance',
                    'icon' => 'fas fa-money-bill-wave',
                    'accessible' => in_array('finance', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'finance.dashboard',
                            'icon' => 'fas fa-chart-pie',
                            'accessible' => in_array('finance.view', $userPermissions)
                        ],
                        [
                            'name' => 'Fees',
                            'route' => 'finance.fees.index',
                            'icon' => 'fas fa-dollar-sign',
                            'accessible' => in_array('finance.fees.view', $userPermissions)
                        ],
                        [
                            'name' => 'Billing',
                            'route' => 'finance.billing.index',
                            'icon' => 'fas fa-file-invoice-dollar',
                            'accessible' => in_array('finance.billing.view', $userPermissions)
                        ],
                        [
                            'name' => 'Payments',
                            'route' => 'finance.payments.index',
                            'icon' => 'fas fa-credit-card',
                            'accessible' => in_array('finance.payments.view', $userPermissions)
                        ],
                        [
                            'name' => 'Reports',
                            'route' => 'finance.reports.index',
                            'icon' => 'fas fa-chart-pie',
                            'accessible' => in_array('finance.reports.view', $userPermissions)
                        ]
                    ]
                ],
                'hr' => [
                    'title' => 'HR',
                    'icon' => 'fas fa-user-tie',
                    'accessible' => in_array('hr', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'hr.index',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('hr.view', $userPermissions)
                        ],
                        [
                            'name' => 'Staff',
                            'route' => 'hr.staff.index',
                            'icon' => 'fas fa-user-tie',
                            'accessible' => in_array('hr.staff.view', $userPermissions)
                        ],
                        [
                            'name' => 'Leave',
                            'route' => 'hr.leave.index',
                            'icon' => 'fas fa-calendar-times',
                            'accessible' => in_array('hr.leave.view', $userPermissions)
                        ],
                        [
                            'name' => 'Payroll',
                            'route' => 'hr.payroll.index',
                            'icon' => 'fas fa-money-check-alt',
                            'accessible' => in_array('hr.payroll.view', $userPermissions)
                        ]
                    ]
                ],
                'library' => [
                    'title' => 'Library',
                    'icon' => 'fas fa-book',
                    'accessible' => in_array('library', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'library.dashboard',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('library.view', $userPermissions)
                        ],
                        [
                            'name' => 'Books',
                            'route' => 'library.books.index',
                            'icon' => 'fas fa-book-open',
                            'accessible' => in_array('library.books.view', $userPermissions)
                        ],
                        [
                            'name' => 'Categories',
                            'route' => 'library.categories.index',
                            'icon' => 'fas fa-tags',
                            'accessible' => in_array('library.categories.view', $userPermissions)
                        ],
                        [
                            'name' => 'Members',
                            'route' => 'library.members.index',
                            'icon' => 'fas fa-users',
                            'accessible' => in_array('library.members.view', $userPermissions)
                        ],
                        [
                            'name' => 'Borrows',
                            'route' => 'library.borrows.index',
                            'icon' => 'fas fa-exchange-alt',
                            'accessible' => in_array('library.borrows.view', $userPermissions)
                        ]
                    ]
                ],
                'attendance' => [
                    'title' => 'Attendance',
                    'icon' => 'fas fa-user-check',
                    'accessible' => in_array('attendance', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'attendance.dashboard',
                            'icon' => 'fas fa-chart-bar',
                            'accessible' => in_array('attendance.view', $userPermissions)
                        ],
                        [
                            'name' => 'Mark Attendance',
                            'route' => 'attendance.mark',
                            'icon' => 'fas fa-clipboard-check',
                            'accessible' => in_array('attendance.mark.view', $userPermissions)
                        ],
                        [
                            'name' => 'Reports',
                            'route' => 'attendance.reports',
                            'icon' => 'fas fa-file-alt',
                            'accessible' => in_array('attendance.reports.view', $userPermissions)
                        ],
                        [
                            'name' => 'Settings',
                            'route' => 'attendance.settings',
                            'icon' => 'fas fa-cogs',
                            'accessible' => in_array('attendance.settings.view', $userPermissions)
                        ]
                    ]
                ],
                'communication' => [
                    'title' => 'Communication',
                    'icon' => 'fas fa-comments',
                    'accessible' => in_array('communication', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'communication.dashboard',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('communication.view', $userPermissions)
                        ],
                        [
                            'name' => 'Inbox',
                            'route' => 'communication.inbox',
                            'icon' => 'fas fa-inbox',
                            'accessible' => in_array('communication.inbox.view', $userPermissions)
                        ],
                        [
                            'name' => 'Compose',
                            'route' => 'communication.compose',
                            'icon' => 'fas fa-edit',
                            'accessible' => in_array('communication.compose.view', $userPermissions)
                        ],
                        [
                            'name' => 'Announcements',
                            'route' => 'communication.dashboard',
                            'icon' => 'fas fa-bullhorn',
                            'accessible' => in_array('communication.announcements.view', $userPermissions)
                        ]
                    ]
                ],
                'settings' => [
                    'title' => 'Settings',
                    'icon' => 'fas fa-cog',
                    'accessible' => in_array('settings', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Settings',
                            'route' => 'settings.index',
                            'icon' => 'fas fa-cog',
                            'accessible' => in_array('settings.view', $userPermissions)
                        ],
                        [
                            'name' => 'Global Settings',
                            'route' => 'settings.global',
                            'icon' => 'fas fa-sliders-h',
                            'accessible' => in_array('settings.view', $userPermissions)
                        ],
                        [
                            'name' => 'Per-School Settings',
                            'route' => 'settings.per_school',
                            'icon' => 'fas fa-school',
                            'accessible' => in_array('settings.view', $userPermissions)
                        ]
                    ]
                ]
            ],
            'user_permissions' => $userPermissions,
            'accessible_modules' => $accessibleModules
        ];

        return response()->json($sidebarData);
    }

    /**
     * Check if user has specific permission
     */
    public function checkPermission(Request $request)
    {
        $user = Auth::user();
        $permission = $request->input('permission');
        
        if (!$user || !$permission) {
            return response()->json(['has_permission' => false]);
        }

        $hasPermission = $user->hasPermission($permission);
        
        return response()->json(['has_permission' => $hasPermission]);
    }

    /**
     * Get user's current permissions
     */
    public function getUserPermissions()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'permissions' => $user->getAllPermissionNames(),
            'roles' => $user->getAllRoleNames(),
            'accessible_modules' => NavigationHelper::getUserModules()
        ]);
    }

    /**
     * Trigger sidebar update for specific user
     */
    public function triggerUpdate(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Clear user's permission cache
        $user->clearPermissionCache();

        return response()->json([
            'message' => 'Sidebar update triggered',
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get last permission update timestamp
     */
    public function getLastUpdate()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get the last time permissions were updated for this user
        $lastUpdate = cache()->get("user_permissions_last_update_{$user->id}", now()->toISOString());

        return response()->json([
            'last_update' => $lastUpdate,
            'current_time' => now()->toISOString()
        ]);
    }
} 