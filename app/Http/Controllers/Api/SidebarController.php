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
                            'name' => 'Online Classes',
                            'route' => 'academic.online-classes.index',
                            'icon' => 'fas fa-video',
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
                            'name' => 'Attendance',
                            'route' => 'academic.attendance.index',
                            'icon' => 'fas fa-user-check',
                            'accessible' => in_array('academic.attendance.view', $userPermissions)
                        ],
                        [
                            'name' => 'Exams',
                            'route' => 'academic.exams.index',
                            'icon' => 'fas fa-file-alt',
                            'accessible' => in_array('academic.exams.view', $userPermissions)
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
                            'name' => 'Question Bank',
                            'route' => 'examination.questions.index',
                            'icon' => 'fas fa-question-circle',
                            'accessible' => in_array('examination.questions.view', $userPermissions)
                        ],
                        [
                            'name' => 'Question Categories',
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
                            'name' => 'Timetable',
                            'route' => 'examination.schedules.timetable',
                            'icon' => 'fas fa-clock',
                            'accessible' => in_array('examination.schedules.view', $userPermissions)
                        ],
                        [
                            'name' => 'Results',
                            'route' => 'examination.results.index',
                            'icon' => 'fas fa-chart-bar',
                            'accessible' => in_array('examination.results.view', $userPermissions)
                        ],
                        [
                            'name' => 'Proctoring',
                            'route' => 'examination.proctoring.index',
                            'icon' => 'fas fa-eye',
                            'accessible' => in_array('examination.proctoring.view', $userPermissions)
                        ],
                        [
                            'name' => 'Online Exams',
                            'route' => 'examination.online.start',
                            'icon' => 'fas fa-laptop',
                            'accessible' => in_array('examination.online.view', $userPermissions)
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
                            'route' => 'finance.index',
                            'icon' => 'fas fa-chart-pie',
                            'accessible' => in_array('finance.view', $userPermissions)
                        ],
                        [
                            'name' => 'Fee Structures',
                            'route' => 'finance.fees.index',
                            'icon' => 'fas fa-coins',
                            'accessible' => in_array('finance.fees.view', $userPermissions)
                        ],
                        [
                            'name' => 'Fee Categories',
                            'route' => 'finance.fee-categories.index',
                            'icon' => 'fas fa-tags',
                            'accessible' => in_array('finance.fees.view', $userPermissions)
                        ],
                        [
                            'name' => 'Fee Types',
                            'route' => 'finance.fee-types.index',
                            'icon' => 'fas fa-list',
                            'accessible' => in_array('finance.fees.view', $userPermissions)
                        ],
                        [
                            'name' => 'Billing & Invoices',
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
                            'name' => 'Receipts',
                            'route' => 'finance.receipts.index',
                            'icon' => 'fas fa-receipt',
                            'accessible' => in_array('finance.receipts.view', $userPermissions)
                        ],
                        [
                            'name' => 'Bank Reconciliation',
                            'route' => 'finance.bank-reconciliation.index',
                            'icon' => 'fas fa-random',
                            'accessible' => in_array('finance.bank-reconciliation.view', $userPermissions)
                        ],
                        [
                            'name' => 'Multi-bank',
                            'route' => 'finance.banks.index',
                            'icon' => 'fas fa-university',
                            'accessible' => in_array('finance.banks.view', $userPermissions)
                        ],
                        [
                            'name' => 'General Ledger',
                            'route' => 'finance.ledger.index',
                            'icon' => 'fas fa-book',
                            'accessible' => in_array('finance.ledger.view', $userPermissions)
                        ],
                        [
                            'name' => 'Forecasting',
                            'route' => 'finance.forecasting.index',
                            'icon' => 'fas fa-chart-line',
                            'accessible' => in_array('finance.forecasting.view', $userPermissions)
                        ],
                        [
                            'name' => 'Tax Management',
                            'route' => 'finance.taxes.index',
                            'icon' => 'fas fa-percentage',
                            'accessible' => in_array('finance.taxes.view', $userPermissions)
                        ],
                        [
                            'name' => 'Online Payments',
                            'route' => 'finance.online-payments.index',
                            'icon' => 'fas fa-globe',
                            'accessible' => in_array('finance.payments.view', $userPermissions)
                        ],
                        [
                            'name' => 'Reports',
                            'route' => 'finance.reports.index',
                            'icon' => 'fas fa-chart-pie',
                            'accessible' => in_array('finance.reports.view', $userPermissions)
                        ],
                        [
                            'name' => 'Settings',
                            'route' => 'finance.settings.index',
                            'icon' => 'fas fa-cogs',
                            'accessible' => in_array('finance.settings.view', $userPermissions)
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
                            'name' => 'Departments',
                            'route' => 'hr.departments.index',
                            'icon' => 'fas fa-building',
                            'accessible' => in_array('hr.departments.view', $userPermissions)
                        ],
                        [
                            'name' => 'Roles',
                            'route' => 'hr.roles.index',
                            'icon' => 'fas fa-user-shield',
                            'accessible' => in_array('hr.roles.view', $userPermissions)
                        ],
                        [
                            'name' => 'Permissions',
                            'route' => 'hr.permissions.index',
                            'icon' => 'fas fa-key',
                            'accessible' => in_array('hr.permissions.view', $userPermissions)
                        ],
                        [
                            'name' => 'Attendance',
                            'route' => 'hr.attendance.index',
                            'icon' => 'fas fa-user-check',
                            'accessible' => in_array('hr.attendance.view', $userPermissions)
                        ],
                        [
                            'name' => 'Leave',
                            'route' => 'hr.leave.index',
                            'icon' => 'fas fa-calendar-times',
                            'accessible' => in_array('hr.leave.view', $userPermissions)
                        ],
                        [
                            'name' => 'Leave Types',
                            'route' => 'hr.leave-type.index',
                            'icon' => 'fas fa-list',
                            'accessible' => in_array('hr.leave.view', $userPermissions)
                        ],
                        [
                            'name' => 'Payroll',
                            'route' => 'hr.payroll.index',
                            'icon' => 'fas fa-money-check-alt',
                            'accessible' => in_array('hr.payroll.view', $userPermissions)
                        ],
                        [
                            'name' => 'Contracts',
                            'route' => 'hr.contracts.index',
                            'icon' => 'fas fa-file-contract',
                            'accessible' => in_array('hr.contract.view', $userPermissions)
                        ],
                        [
                            'name' => 'Performance Reviews',
                            'route' => 'hr.performance_reviews.index',
                            'icon' => 'fas fa-star',
                            'accessible' => in_array('hr.performance.view', $userPermissions)
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
                            'name' => 'Outbox',
                            'route' => 'communication.outbox',
                            'icon' => 'fas fa-paper-plane',
                            'accessible' => in_array('communication.outbox.view', $userPermissions)
                        ],
                        [
                            'name' => 'Compose',
                            'route' => 'communication.compose',
                            'icon' => 'fas fa-edit',
                            'accessible' => in_array('communication.compose.view', $userPermissions)
                        ],
                        [
                            'name' => 'Groups',
                            'route' => 'communication.groups',
                            'icon' => 'fas fa-users',
                            'accessible' => in_array('communication.groups.view', $userPermissions)
                        ],
                        [
                            'name' => 'Broadcasts',
                            'route' => 'communication.broadcasts',
                            'icon' => 'fas fa-bullhorn',
                            'accessible' => in_array('communication.broadcasts.view', $userPermissions)
                        ],
                        [
                            'name' => 'Announcements',
                            'route' => 'communication.announcements',
                            'icon' => 'fas fa-bullhorn',
                            'accessible' => in_array('communication.announcements.view', $userPermissions)
                        ],
                        [
                            'name' => 'Notifications',
                            'route' => 'communication.notifications',
                            'icon' => 'fas fa-bell',
                            'accessible' => in_array('communication.notifications.view', $userPermissions)
                        ],
                        [
                            'name' => 'Templates',
                            'route' => 'communication.templates',
                            'icon' => 'fas fa-file-alt',
                            'accessible' => in_array('communication.templates.view', $userPermissions)
                        ],
                        [
                            'name' => 'Settings',
                            'route' => 'communication.settings',
                            'icon' => 'fas fa-cogs',
                            'accessible' => in_array('communication.settings.view', $userPermissions)
                        ]
                    ]
                ],
                'hostel' => [
                    'title' => 'Hostel',
                    'icon' => 'fas fa-hotel',
                    'accessible' => in_array('hostel', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'hostel.dashboard',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('hostel.view', $userPermissions)
                        ],
                        [
                            'name' => 'Hostels',
                            'route' => 'hostel.hostels.index',
                            'icon' => 'fas fa-building',
                            'accessible' => in_array('hostel.hostels.view', $userPermissions)
                        ],
                        [
                            'name' => 'Room Allocations',
                            'route' => 'hostel.room_allocations.index',
                            'icon' => 'fas fa-bed',
                            'accessible' => in_array('hostel.allocations.view', $userPermissions)
                        ],
                        [
                            'name' => 'Rooms',
                            'route' => 'hostel.rooms.index',
                            'icon' => 'fas fa-door-open',
                            'accessible' => in_array('hostel.rooms.view', $userPermissions)
                        ],
                        [
                            'name' => 'Floors',
                            'route' => 'hostel.floors.index',
                            'icon' => 'fas fa-building',
                            'accessible' => in_array('hostel.floors.view', $userPermissions)
                        ],
                        [
                            'name' => 'Beds',
                            'route' => 'hostel.beds.index',
                            'icon' => 'fas fa-bed',
                            'accessible' => in_array('hostel.beds.view', $userPermissions)
                        ],
                        [
                            'name' => 'Hostel Fees',
                            'route' => 'hostel.fees.index',
                            'icon' => 'fas fa-coins',
                            'accessible' => in_array('hostel.fees.view', $userPermissions)
                        ],
                        [
                            'name' => 'Issues',
                            'route' => 'hostel.issues.index',
                            'icon' => 'fas fa-exclamation-triangle',
                            'accessible' => in_array('hostel.issues.view', $userPermissions)
                        ],
                        [
                            'name' => 'Leave Management',
                            'route' => 'hostel.leave_requests.index',
                            'icon' => 'fas fa-plane-departure',
                            'accessible' => in_array('hostel.leave.view', $userPermissions)
                        ],
                        [
                            'name' => 'Visitors',
                            'route' => 'hostel.visitors.index',
                            'icon' => 'fas fa-user-friends',
                            'accessible' => in_array('hostel.visitors.view', $userPermissions)
                        ],
                        [
                            'name' => 'Announcements',
                            'route' => 'hostel.announcements.index',
                            'icon' => 'fas fa-bullhorn',
                            'accessible' => in_array('hostel.announcements.view', $userPermissions)
                        ],
                        [
                            'name' => 'Wardens',
                            'route' => 'hostel.wardens.index',
                            'icon' => 'fas fa-user-shield',
                            'accessible' => in_array('hostel.wardens.view', $userPermissions)
                        ],
                        [
                            'name' => 'Reports',
                            'route' => 'hostel.reports.dashboard',
                            'icon' => 'fas fa-chart-bar',
                            'accessible' => in_array('hostel.reports.view', $userPermissions)
                        ]
                    ]
                ],
                'transport' => [
                    'title' => 'Transport',
                    'icon' => 'fas fa-bus',
                    'accessible' => in_array('transport', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'transport.index',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('transport.view', $userPermissions)
                        ],
                        [
                            'name' => 'Vehicles',
                            'route' => 'transport.vehicles.index',
                            'icon' => 'fas fa-car',
                            'accessible' => in_array('transport.vehicles.view', $userPermissions)
                        ],
                        [
                            'name' => 'Routes',
                            'route' => 'transport.routes.index',
                            'icon' => 'fas fa-route',
                            'accessible' => in_array('transport.routes.view', $userPermissions)
                        ],
                        [
                            'name' => 'Drivers',
                            'route' => 'transport.drivers.index',
                            'icon' => 'fas fa-user-tie',
                            'accessible' => in_array('transport.drivers.view', $userPermissions)
                        ],
                        [
                            'name' => 'Trips',
                            'route' => 'transport.trips.index',
                            'icon' => 'fas fa-route',
                            'accessible' => in_array('transport.trips.view', $userPermissions)
                        ],
                        [
                            'name' => 'Reports',
                            'route' => 'transport.reports',
                            'icon' => 'fas fa-chart-bar',
                            'accessible' => in_array('transport.reports.view', $userPermissions)
                        ]
                    ]
                ],
                'timetable' => [
                    'title' => 'Timetable',
                    'icon' => 'fas fa-calendar-alt',
                    'accessible' => in_array('timetable', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'timetables.dashboard',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('timetable.view', $userPermissions)
                        ],
                        [
                            'name' => 'Class Schedules',
                            'route' => 'class_schedules.index',
                            'icon' => 'fas fa-calendar',
                            'accessible' => in_array('timetable.schedules.view', $userPermissions)
                        ],
                        [
                            'name' => 'Teacher Availabilities',
                            'route' => 'teacher_availabilities.index',
                            'icon' => 'fas fa-user-clock',
                            'accessible' => in_array('timetable.teacher_availabilities.view', $userPermissions)
                        ],
                        [
                            'name' => 'Rooms',
                            'route' => 'rooms.index',
                            'icon' => 'fas fa-door-open',
                            'accessible' => in_array('timetable.rooms.view', $userPermissions)
                        ],
                        [
                            'name' => 'Room Allocations',
                            'route' => 'room_allocations.index',
                            'icon' => 'fas fa-th-large',
                            'accessible' => in_array('timetable.room_allocations.view', $userPermissions)
                        ],
                        [
                            'name' => 'Room Availabilities',
                            'route' => 'room_availabilities.index',
                            'icon' => 'fas fa-clock',
                            'accessible' => in_array('timetable.room_availabilities.view', $userPermissions)
                        ],
                        [
                            'name' => 'Calendar',
                            'route' => 'timetable.calendar',
                            'icon' => 'fas fa-calendar-alt',
                            'accessible' => in_array('timetable.calendar.view', $userPermissions)
                        ],
                        [
                            'name' => 'Conflicts',
                            'route' => 'timetable.conflicts',
                            'icon' => 'fas fa-exclamation-triangle',
                            'accessible' => in_array('timetable.conflicts.view', $userPermissions)
                        ],
                        [
                            'name' => 'Analytics',
                            'route' => 'timetable.analytics',
                            'icon' => 'fas fa-chart-line',
                            'accessible' => in_array('timetable.analytics.view', $userPermissions)
                        ],
                        [
                            'name' => 'Reports',
                            'route' => 'timetables.reports',
                            'icon' => 'fas fa-file-alt',
                            'accessible' => in_array('timetable.reports.view', $userPermissions)
                        ],
                        [
                            'name' => 'Settings',
                            'route' => 'timetable.settings',
                            'icon' => 'fas fa-cogs',
                            'accessible' => in_array('timetable.settings.view', $userPermissions)
                        ]
                    ]
                ],
                'portal' => [
                    'title' => 'Student/Parent Portal',
                    'icon' => 'fas fa-user-friends',
                    'accessible' => in_array('portal', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'portal.dashboard',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('portal.view', $userPermissions)
                        ],
                        [
                            'name' => 'Academics',
                            'route' => 'portal.academics',
                            'icon' => 'fas fa-graduation-cap',
                            'accessible' => in_array('portal.academics.view', $userPermissions)
                        ],
                        [
                            'name' => 'Schedule',
                            'route' => 'portal.schedule',
                            'icon' => 'fas fa-calendar-alt',
                            'accessible' => in_array('portal.schedule.view', $userPermissions)
                        ],
                        [
                            'name' => 'Materials',
                            'route' => 'portal.materials',
                            'icon' => 'fas fa-book',
                            'accessible' => in_array('portal.materials.view', $userPermissions)
                        ],
                        [
                            'name' => 'Assignments',
                            'route' => 'portal.assignments',
                            'icon' => 'fas fa-book-open',
                            'accessible' => in_array('portal.assignments.view', $userPermissions)
                        ],
                        [
                            'name' => 'Finance',
                            'route' => 'portal.finance',
                            'icon' => 'fas fa-money-bill-wave',
                            'accessible' => in_array('portal.finance.view', $userPermissions)
                        ],
                        [
                            'name' => 'Communication',
                            'route' => 'portal.communication',
                            'icon' => 'fas fa-comments',
                            'accessible' => in_array('portal.communication.view', $userPermissions)
                        ],
                        [
                            'name' => 'Library Search',
                            'route' => 'portal.library.search',
                            'icon' => 'fas fa-search',
                            'accessible' => in_array('portal.library.view', $userPermissions)
                        ],
                        [
                            'name' => 'LMS',
                            'route' => 'portal.lms',
                            'icon' => 'fas fa-chalkboard-teacher',
                            'accessible' => in_array('portal.lms.view', $userPermissions)
                        ],
                        [
                            'name' => 'Hostel',
                            'route' => 'portal.hostel',
                            'icon' => 'fas fa-hotel',
                            'accessible' => in_array('portal.hostel.view', $userPermissions)
                        ],
                        [
                            'name' => 'Transport',
                            'route' => 'portal.transport',
                            'icon' => 'fas fa-bus',
                            'accessible' => in_array('portal.transport.view', $userPermissions)
                        ],
                        [
                            'name' => 'Welfare',
                            'route' => 'portal.welfare',
                            'icon' => 'fas fa-heart',
                            'accessible' => in_array('portal.welfare.view', $userPermissions)
                        ],
                        [
                            'name' => 'Profile',
                            'route' => 'portal.profile',
                            'icon' => 'fas fa-user',
                            'accessible' => in_array('portal.profile.view', $userPermissions)
                        ]
                    ]
                ],
                'document' => [
                    'title' => 'Document',
                    'icon' => 'fas fa-file-alt',
                    'accessible' => in_array('document', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'document.index',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('document.view', $userPermissions)
                        ],
                        [
                            'name' => 'Upload',
                            'route' => 'document.upload',
                            'icon' => 'fas fa-upload',
                            'accessible' => in_array('document.upload.view', $userPermissions)
                        ],
                        [
                            'name' => 'Manage',
                            'route' => 'document.manage',
                            'icon' => 'fas fa-cogs',
                            'accessible' => in_array('document.manage.view', $userPermissions)
                        ]
                    ]
                ],
                'notification' => [
                    'title' => 'Notification',
                    'icon' => 'fas fa-bell',
                    'accessible' => in_array('notification', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'notification.index',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('notification.view', $userPermissions)
                        ],
                        [
                            'name' => 'Manage',
                            'route' => 'notification.manage',
                            'icon' => 'fas fa-cogs',
                            'accessible' => in_array('notification.manage.view', $userPermissions)
                        ]
                    ]
                ],
                'api' => [
                    'title' => 'API',
                    'icon' => 'fas fa-code',
                    'accessible' => in_array('api', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'route' => 'api.index',
                            'icon' => 'fas fa-home',
                            'accessible' => in_array('api.view', $userPermissions)
                        ],
                        [
                            'name' => 'Manage',
                            'route' => 'api.manage',
                            'icon' => 'fas fa-cogs',
                            'accessible' => in_array('api.manage.view', $userPermissions)
                        ],
                        [
                            'name' => 'Users API',
                            'route' => 'api.users',
                            'icon' => 'fas fa-users',
                            'accessible' => in_array('api.users.view', $userPermissions)
                        ],
                        [
                            'name' => 'Students API',
                            'route' => 'api.students',
                            'icon' => 'fas fa-user-graduate',
                            'accessible' => in_array('api.students.view', $userPermissions)
                        ],
                        [
                            'name' => 'Staff API',
                            'route' => 'api.staff',
                            'icon' => 'fas fa-user-tie',
                            'accessible' => in_array('api.staff.view', $userPermissions)
                        ],
                        [
                            'name' => 'Classes API',
                            'route' => 'api.classes',
                            'icon' => 'fas fa-chalkboard',
                            'accessible' => in_array('api.classes.view', $userPermissions)
                        ],
                        [
                            'name' => 'Statistics API',
                            'route' => 'api.stats',
                            'icon' => 'fas fa-chart-bar',
                            'accessible' => in_array('api.stats.view', $userPermissions)
                        ]
                    ]
                ],
                'chatbot' => [
                    'title' => 'AI Assistant',
                    'icon' => 'fas fa-robot',
                    'accessible' => in_array('chatbot', $accessibleModules),
                    'items' => [
                        [
                            'name' => 'Chat Interface',
                            'route' => 'chatbot.index',
                            'icon' => 'fas fa-comments',
                            'accessible' => in_array('chatbot.view', $userPermissions)
                        ],
                        [
                            'name' => 'Dashboard',
                            'route' => 'chatbot.admin.dashboard',
                            'icon' => 'fas fa-tachometer-alt',
                            'accessible' => in_array('chatbot.admin', $userPermissions)
                        ],
                        [
                            'name' => 'Settings',
                            'route' => 'chatbot.admin.settings',
                            'icon' => 'fas fa-cog',
                            'accessible' => in_array('chatbot.admin', $userPermissions)
                        ],
                        [
                            'name' => 'Usage Statistics',
                            'route' => 'chatbot.admin.usage',
                            'icon' => 'fas fa-chart-bar',
                            'accessible' => in_array('chatbot.admin', $userPermissions)
                        ],
                        [
                            'name' => 'AI Models',
                            'route' => 'chatbot.admin.models',
                            'icon' => 'fas fa-brain',
                            'accessible' => in_array('chatbot.admin', $userPermissions)
                        ],
                        [
                            'name' => 'System Health',
                            'route' => 'chatbot.admin.health',
                            'icon' => 'fas fa-heartbeat',
                            'accessible' => in_array('chatbot.admin', $userPermissions)
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
        $request->validate([
            'permission' => 'required|string|max:255',
        ]);

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
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

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