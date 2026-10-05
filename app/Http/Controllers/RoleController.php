<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        // Sample roles data
        $roles = [
            [
                'id' => 1,
                'name' => 'admin',
                'display_name' => 'System Administrator',
                'description' => 'Full system access with all permissions',
                'status' => 'active',
                'users_count' => 3,
                'created_at' => '2024-01-15',
                'permissions' => ['Create Permissions', 'Edit School', 'Add Role', 'View All Permissions']
            ],
            [
                'id' => 2,
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Highest level of system access',
                'status' => 'active',
                'users_count' => 1,
                'created_at' => '2024-01-10',
                'permissions' => ['Create Permissions', 'Edit School', 'Add Role', 'View All Permissions']
            ],
            [
                'id' => 3,
                'name' => 'academic_admin',
                'display_name' => 'Academic Administrator',
                'description' => 'Manages academic operations and curriculum',
                'status' => 'active',
                'users_count' => 2,
                'created_at' => '2024-01-20',
                'permissions' => ['View Students', 'Add Students', 'Schedule Exams', 'Enter Marks']
            ],
            [
                'id' => 4,
                'name' => 'teacher',
                'display_name' => 'Teacher',
                'description' => 'Classroom teacher with subject-specific permissions',
                'status' => 'active',
                'users_count' => 45,
                'created_at' => '2024-02-01',
                'permissions' => ['View Students', 'Enter Marks', 'View Attendance']
            ],
            [
                'id' => 5,
                'name' => 'head_teacher',
                'display_name' => 'Head Teacher',
                'description' => 'Senior teacher with department oversight',
                'status' => 'active',
                'users_count' => 8,
                'created_at' => '2024-01-25',
                'permissions' => ['View Students', 'Add Students', 'Schedule Exams', 'Enter Marks']
            ],
            [
                'id' => 6,
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Regular student with limited access',
                'status' => 'active',
                'users_count' => 1250,
                'created_at' => '2024-02-10',
                'permissions' => ['View Own Grades', 'View Own Attendance']
            ],
            [
                'id' => 7,
                'name' => 'parent',
                'display_name' => 'Parent',
                'description' => 'Parent with access to child information',
                'status' => 'active',
                'users_count' => 980,
                'created_at' => '2024-02-15',
                'permissions' => ['View Child Grades', 'View Child Attendance']
            ],
            [
                'id' => 8,
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Financial operations and fee management',
                'status' => 'active',
                'users_count' => 3,
                'created_at' => '2024-01-30',
                'permissions' => ['View Fees', 'Process Payments', 'Generate Reports']
            ],
            [
                'id' => 9,
                'name' => 'librarian',
                'display_name' => 'Librarian',
                'description' => 'Library management and book operations',
                'status' => 'active',
                'users_count' => 2,
                'created_at' => '2024-02-05',
                'permissions' => ['Manage Books', 'Issue Books', 'View Library Reports']
            ],
            [
                'id' => 10,
                'name' => 'hr_manager',
                'display_name' => 'HR Manager',
                'description' => 'Human resources and staff management',
                'status' => 'active',
                'users_count' => 2,
                'created_at' => '2024-01-28',
                'permissions' => ['View Staff', 'Manage Leave', 'Process Payroll']
            ]
        ];

        // Filter roles based on search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $roles = array_filter($roles, function($role) use ($search) {
                return str_contains(strtolower($role['name']), $search) ||
                       str_contains(strtolower($role['display_name']), $search) ||
                       str_contains(strtolower($role['description']), $search);
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $roles = array_filter($roles, function($role) use ($request) {
                return $role['status'] === $request->status;
            });
        }

        // Filter by module
        if ($request->filled('module') && $request->module !== 'all') {
            $roles = array_filter($roles, function($role) use ($request) {
                // This would be more complex in a real implementation
                return true; // Placeholder
            });
        }

        // Calculate stats
        $totalRoles = count($roles);
        $activeRoles = count(array_filter($roles, function($role) {
            return $role['status'] === 'active';
        }));

        $stats = [
            'total_roles' => $totalRoles,
            'active_roles' => $activeRoles
        ];

        // Sample permissions data for the dynamic panel
        $modules = [
            'academic' => [
                'name' => 'Academic',
                'icon' => 'book-open',
                'permissions' => [
                    'view_students' => 'View Students',
                    'add_students' => 'Add Students',
                    'edit_students' => 'Edit Students',
                    'delete_students' => 'Delete Students',
                    'view_classes' => 'View Classes',
                    'manage_classes' => 'Manage Classes'
                ]
            ],
            'exams' => [
                'name' => 'Exams',
                'icon' => 'file-text',
                'permissions' => [
                    'schedule_exams' => 'Schedule Exams',
                    'enter_marks' => 'Enter Marks',
                    'view_results' => 'View Results',
                    'delete_results' => 'Delete Results',
                    'generate_reports' => 'Generate Reports'
                ]
            ],
            'finance' => [
                'name' => 'Finance',
                'icon' => 'dollar-sign',
                'permissions' => [
                    'view_fees' => 'View Fees',
                    'process_payments' => 'Process Payments',
                    'manage_invoices' => 'Manage Invoices',
                    'generate_reports' => 'Generate Reports',
                    'manage_scholarships' => 'Manage Scholarships'
                ]
            ],
            'library' => [
                'name' => 'Library',
                'icon' => 'book',
                'permissions' => [
                    'manage_books' => 'Manage Books',
                    'issue_books' => 'Issue Books',
                    'return_books' => 'Return Books',
                    'view_library_reports' => 'View Library Reports',
                    'manage_members' => 'Manage Members'
                ]
            ],
            'hr' => [
                'name' => 'HR',
                'icon' => 'users',
                'permissions' => [
                    'view_staff' => 'View Staff',
                    'manage_staff' => 'Manage Staff',
                    'manage_leave' => 'Manage Leave',
                    'process_payroll' => 'Process Payroll',
                    'view_attendance' => 'View Attendance'
                ]
            ],
            'system' => [
                'name' => 'System',
                'icon' => 'settings',
                'permissions' => [
                    'manage_roles' => 'Manage Roles',
                    'manage_permissions' => 'Manage Permissions',
                    'view_logs' => 'View Logs',
                    'system_settings' => 'System Settings',
                    'backup_restore' => 'Backup & Restore'
                ]
            ]
        ];

        return view('roles.index', compact('roles', 'stats', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ]);

        // In a real implementation, this would save to database
        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'role' => [
                'id' => rand(100, 999),
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'status' => 'active',
                'users_count' => 0,
                'created_at' => now()->format('Y-m-d')
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ]);

        // In a real implementation, this would update the database
        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully'
        ]);
    }

    public function destroy($id)
    {
        // In a real implementation, this would delete from database
        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    public function getPermissions($roleId)
    {
        // Sample permissions for a role
        $permissions = [
            'academic' => [
                'view_students' => true,
                'add_students' => true,
                'edit_students' => false,
                'delete_students' => false,
                'view_classes' => true,
                'manage_classes' => false
            ],
            'exams' => [
                'schedule_exams' => true,
                'enter_marks' => true,
                'view_results' => true,
                'delete_results' => false,
                'generate_reports' => false
            ],
            'finance' => [
                'view_fees' => true,
                'process_payments' => false,
                'manage_invoices' => false,
                'generate_reports' => false,
                'manage_scholarships' => false
            ],
            'library' => [
                'manage_books' => false,
                'issue_books' => false,
                'return_books' => false,
                'view_library_reports' => false,
                'manage_members' => false
            ],
            'hr' => [
                'view_staff' => false,
                'manage_staff' => false,
                'manage_leave' => false,
                'process_payroll' => false,
                'view_attendance' => false
            ],
            'system' => [
                'manage_roles' => false,
                'manage_permissions' => false,
                'view_logs' => false,
                'system_settings' => false,
                'backup_restore' => false
            ]
        ];

        return response()->json($permissions);
    }
}
