<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class CoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default school if it doesn't exist
        $school = School::firstOrCreate(
            ['domain' => 'dunco.local'],
            [
                'name' => 'Dunco Academy',
                'code' => 'DA001',
                'motto' => 'Excellence in Education',
                'is_active' => true,
            ]
        );

        // Create comprehensive roles for all modules
        $roles = [
            // Core System Roles
            ['name' => 'admin', 'display_name' => 'System Administrator', 'description' => 'Full system administrator with all permissions', 'is_system' => true],
            ['name' => 'super_admin', 'display_name' => 'Super Administrator', 'description' => 'Highest level administrator with complete system access', 'is_system' => true],
            
            // Academic Module Roles
            ['name' => 'academic_admin', 'display_name' => 'Academic Administrator', 'description' => 'Administrator for academic module with full access', 'is_system' => true],
            ['name' => 'academic_coordinator', 'display_name' => 'Academic Coordinator', 'description' => 'Coordinates academic activities and curriculum', 'is_system' => true],
            ['name' => 'curriculum_manager', 'display_name' => 'Curriculum Manager', 'description' => 'Manages curriculum development and implementation', 'is_system' => true],
            ['name' => 'subject_coordinator', 'display_name' => 'Subject Coordinator', 'description' => 'Coordinates specific subject areas', 'is_system' => true],
            
            // Teacher and Student Roles
            ['name' => 'teacher', 'display_name' => 'Teacher', 'description' => 'School teacher with limited administrative access', 'is_system' => true],
            ['name' => 'head_teacher', 'display_name' => 'Head Teacher', 'description' => 'Senior teacher with additional administrative responsibilities', 'is_system' => true],
            ['name' => 'student', 'display_name' => 'Student', 'description' => 'Student with basic access to their own data', 'is_system' => true],
            ['name' => 'senior_student', 'display_name' => 'Senior Student', 'description' => 'Senior student with additional privileges', 'is_system' => true],
            
            // Parent and Guardian Roles
            ['name' => 'parent', 'display_name' => 'Parent', 'description' => 'Parent with access to their children\'s information', 'is_system' => true],
            ['name' => 'guardian', 'display_name' => 'Guardian', 'description' => 'Legal guardian with access to ward\'s information', 'is_system' => true],
            
            // HR Module Roles
            ['name' => 'hr_manager', 'display_name' => 'HR Manager', 'description' => 'Manages human resources and staff administration', 'is_system' => true],
            ['name' => 'hr_officer', 'display_name' => 'HR Officer', 'description' => 'Handles HR operations and staff records', 'is_system' => true],
            ['name' => 'recruitment_officer', 'display_name' => 'Recruitment Officer', 'description' => 'Handles staff recruitment and hiring', 'is_system' => true],
            ['name' => 'payroll_officer', 'display_name' => 'Payroll Officer', 'description' => 'Manages staff payroll and benefits', 'is_system' => true],
            
            // Finance Module Roles
            ['name' => 'finance_manager', 'display_name' => 'Finance Manager', 'description' => 'Manages financial operations and budgeting', 'is_system' => true],
            ['name' => 'accountant', 'display_name' => 'Accountant', 'description' => 'Handles accounting and financial records', 'is_system' => true],
            ['name' => 'billing_officer', 'display_name' => 'Billing Officer', 'description' => 'Handles student billing and fee collection', 'is_system' => true],
            ['name' => 'treasurer', 'display_name' => 'Treasurer', 'description' => 'Manages school treasury and financial planning', 'is_system' => true],
            
            // Examination Module Roles
            ['name' => 'exam_officer', 'display_name' => 'Examination Officer', 'description' => 'Manages examination schedules and procedures', 'is_system' => true],
            ['name' => 'examiner', 'display_name' => 'Examiner', 'description' => 'Conducts and grades examinations', 'is_system' => true],
            ['name' => 'invigilator', 'display_name' => 'Invigilator', 'description' => 'Supervises examination sessions', 'is_system' => true],
            ['name' => 'results_officer', 'display_name' => 'Results Officer', 'description' => 'Manages examination results and transcripts', 'is_system' => true],
            
            // Library Module Roles
            ['name' => 'librarian', 'display_name' => 'Librarian', 'description' => 'Manages library resources and services', 'is_system' => true],
            ['name' => 'library_assistant', 'display_name' => 'Library Assistant', 'description' => 'Assists with library operations', 'is_system' => true],
            ['name' => 'elibrary_manager', 'display_name' => 'E-Library Manager', 'description' => 'Manages digital library resources', 'is_system' => true],
            
            // Hostel Module Roles
            ['name' => 'hostel_manager', 'display_name' => 'Hostel Manager', 'description' => 'Manages hostel facilities and accommodation', 'is_system' => true],
            ['name' => 'warden', 'display_name' => 'Warden', 'description' => 'Supervises hostel residents and facilities', 'is_system' => true],
            ['name' => 'hostel_assistant', 'display_name' => 'Hostel Assistant', 'description' => 'Assists with hostel operations', 'is_system' => true],
            
            // Transport Module Roles
            ['name' => 'transport_manager', 'display_name' => 'Transport Manager', 'description' => 'Manages school transportation services', 'is_system' => true],
            ['name' => 'transport_coordinator', 'display_name' => 'Transport Coordinator', 'description' => 'Coordinates transport schedules and routes', 'is_system' => true],
            ['name' => 'driver', 'display_name' => 'Driver', 'description' => 'School transport driver', 'is_system' => true],
            
            // Cafeteria Module Roles
            ['name' => 'cafeteria_manager', 'display_name' => 'Cafeteria Manager', 'description' => 'Manages cafeteria operations and food services', 'is_system' => true],
            ['name' => 'cafeteria_staff', 'display_name' => 'Cafeteria Staff', 'description' => 'Cafeteria service staff', 'is_system' => true],
            
            // Health and Welfare Roles
            ['name' => 'nurse', 'display_name' => 'School Nurse', 'description' => 'Provides health services to students and staff', 'is_system' => true],
            ['name' => 'counselor', 'display_name' => 'School Counselor', 'description' => 'Provides counseling and guidance services', 'is_system' => true],
            ['name' => 'welfare_officer', 'display_name' => 'Welfare Officer', 'description' => 'Manages student and staff welfare programs', 'is_system' => true],
            
            // Administrative Support Roles
            ['name' => 'receptionist', 'display_name' => 'Receptionist', 'description' => 'Handles front desk operations and inquiries', 'is_system' => true],
            ['name' => 'secretary', 'display_name' => 'Secretary', 'description' => 'Provides administrative support', 'is_system' => true],
            ['name' => 'clerk', 'display_name' => 'Clerk', 'description' => 'Handles clerical and administrative tasks', 'is_system' => true],
            
            // IT and Technical Roles
            ['name' => 'it_manager', 'display_name' => 'IT Manager', 'description' => 'Manages IT infrastructure and systems', 'is_system' => true],
            ['name' => 'system_administrator', 'display_name' => 'System Administrator', 'description' => 'Manages system administration and maintenance', 'is_system' => true],
            ['name' => 'data_analyst', 'display_name' => 'Data Analyst', 'description' => 'Analyzes school data and generates reports', 'is_system' => true],
            
            // Compliance and Audit Roles
            ['name' => 'compliance_officer', 'display_name' => 'Compliance Officer', 'description' => 'Ensures regulatory compliance and standards', 'is_system' => true],
            ['name' => 'auditor', 'display_name' => 'Auditor', 'description' => 'Conducts internal audits and reviews', 'is_system' => true],
            ['name' => 'quality_assurance', 'display_name' => 'Quality Assurance Officer', 'description' => 'Ensures quality standards and procedures', 'is_system' => true],
            
            // Communication and Marketing Roles
            ['name' => 'communication_officer', 'display_name' => 'Communication Officer', 'description' => 'Manages internal and external communications', 'is_system' => true],
            ['name' => 'marketing_officer', 'display_name' => 'Marketing Officer', 'description' => 'Handles school marketing and public relations', 'is_system' => true],
            ['name' => 'public_relations', 'display_name' => 'Public Relations Officer', 'description' => 'Manages public relations and media', 'is_system' => true],
            
            // Asset and Inventory Management
            ['name' => 'asset_manager', 'display_name' => 'Asset Manager', 'description' => 'Manages school assets and equipment', 'is_system' => true],
            ['name' => 'inventory_manager', 'display_name' => 'Inventory Manager', 'description' => 'Manages inventory and supplies', 'is_system' => true],
            ['name' => 'procurement_officer', 'display_name' => 'Procurement Officer', 'description' => 'Handles procurement and purchasing', 'is_system' => true],
            
            // Specialized Module Roles
            ['name' => 'lms_manager', 'display_name' => 'LMS Manager', 'description' => 'Manages Learning Management System', 'is_system' => true],
            ['name' => 'timetable_manager', 'display_name' => 'Timetable Manager', 'description' => 'Manages class schedules and timetables', 'is_system' => true],
            ['name' => 'attendance_officer', 'display_name' => 'Attendance Officer', 'description' => 'Manages student and staff attendance', 'is_system' => true],
            ['name' => 'document_controller', 'display_name' => 'Document Controller', 'description' => 'Manages document control and records', 'is_system' => true],
            ['name' => 'notification_manager', 'display_name' => 'Notification Manager', 'description' => 'Manages system notifications and alerts', 'is_system' => true],
            ['name' => 'settings_manager', 'display_name' => 'Settings Manager', 'description' => 'Manages system settings and configurations', 'is_system' => true],
            ['name' => 'api_user', 'display_name' => 'API User', 'description' => 'User with API access privileges', 'is_system' => true],
            ['name' => 'analytics_manager', 'display_name' => 'Analytics Manager', 'description' => 'Manages analytics and reporting', 'is_system' => true],
            ['name' => 'chatbot_admin', 'display_name' => 'Chatbot Administrator', 'description' => 'Manages chatbot functionality', 'is_system' => true],
            ['name' => 'marketplace_manager', 'display_name' => 'Marketplace Manager', 'description' => 'Manages school marketplace operations', 'is_system' => true],
            ['name' => 'pwa_manager', 'display_name' => 'PWA Manager', 'description' => 'Manages Progressive Web App features', 'is_system' => true],
            ['name' => 'researcher', 'display_name' => 'Researcher', 'description' => 'Conducts research and analysis', 'is_system' => true],
            ['name' => 'alumni_manager', 'display_name' => 'Alumni Manager', 'description' => 'Manages alumni relations and programs', 'is_system' => true],
            ['name' => 'localization_manager', 'display_name' => 'Localization Manager', 'description' => 'Manages multi-language support', 'is_system' => true],
            
            // Guest and Limited Access Roles
            ['name' => 'guest', 'display_name' => 'Guest', 'description' => 'Limited access guest user', 'is_system' => true],
            ['name' => 'viewer', 'display_name' => 'Viewer', 'description' => 'Read-only access to specific modules', 'is_system' => true],
        ];

        // Create all roles
        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        // Create default permissions if they don't exist
        $permissions = [
            ['name' => 'schools.view', 'display_name' => 'View Schools', 'module' => 'Core'],
            ['name' => 'schools.create', 'display_name' => 'Create Schools', 'module' => 'Core'],
            ['name' => 'schools.edit', 'display_name' => 'Edit Schools', 'module' => 'Core'],
            ['name' => 'schools.delete', 'display_name' => 'Delete Schools', 'module' => 'Core'],
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'Core'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'Core'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'Core'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'Core'],
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'module' => 'Core'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'module' => 'Core'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'module' => 'Core'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'module' => 'Core'],
            ['name' => 'permissions.view', 'display_name' => 'View Permissions', 'module' => 'Core'],
            ['name' => 'permissions.create', 'display_name' => 'Create Permissions', 'module' => 'Core'],
            ['name' => 'permissions.edit', 'display_name' => 'Edit Permissions', 'module' => 'Core'],
            ['name' => 'permissions.delete', 'display_name' => 'Delete Permissions', 'module' => 'Core'],
            ['name' => 'audit.view', 'display_name' => 'View Audit Logs', 'module' => 'Core'],
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'Core'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'Core'],
            ['name' => 'timetable.view', 'display_name' => 'View Timetables', 'description' => 'View timetables and schedules', 'module' => 'Timetable'],
            ['name' => 'timetable.create', 'display_name' => 'Create Timetables', 'description' => 'Create timetables and schedules', 'module' => 'Timetable'],
            ['name' => 'timetable.edit', 'display_name' => 'Edit Timetables', 'description' => 'Edit timetables and schedules', 'module' => 'Timetable'],
            ['name' => 'timetable.delete', 'display_name' => 'Delete Timetables', 'description' => 'Delete timetables and schedules', 'module' => 'Timetable'],
            ['name' => 'timetable.export', 'display_name' => 'Export Timetables', 'description' => 'Export or print timetables and schedules', 'module' => 'Timetable'],
            ['name' => 'timetable.manage', 'display_name' => 'Manage Timetables', 'description' => 'Full access to timetable management', 'module' => 'Timetable'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create admin user if it doesn't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@dunco.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('password'),
                'school_id' => $school->id,
            ]
        );

        // Get admin role
        $adminRole = Role::where('name', 'admin')->first();

        // Assign admin role to admin user if not already assigned
        if (!$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
            $adminUser->roles()->attach($adminRole->id);
        }

        // Assign all permissions to admin role if not already assigned
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id')->toArray());

        // Assign timetable permissions to admin and timetable_manager roles if they exist
        $timetablePermissions = Permission::whereIn('name', ['timetable.view', 'timetable.create', 'timetable.edit', 'timetable.delete', 'timetable.export', 'timetable.manage'])->pluck('id')->toArray();
        $adminRole->permissions()->syncWithoutDetaching($timetablePermissions);

        $timetableManagerRole = Role::where('name', 'timetable_manager')->first();
        if ($timetableManagerRole) {
            $timetableManagerRole->permissions()->syncWithoutDetaching($timetablePermissions);
        }

        // Seed default school settings if they don't exist
        $defaultSettings = [
            [
                'key' => 'school_name',
                'value' => $school->name,
                'type' => 'string',
                'description' => 'The name of the school',
            ],
            [
                'key' => 'school_motto',
                'value' => $school->motto,
                'type' => 'string',
                'description' => 'The motto of the school',
            ],
            [
                'key' => 'school_domain',
                'value' => $school->domain,
                'type' => 'string',
                'description' => 'The domain of the school',
            ],
        ];

        foreach ($defaultSettings as $setting) {
            \App\Models\SchoolSetting::firstOrCreate(
                [
                    'school_id' => $school->id,
                    'key' => $setting['key'],
                ],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // Seed a sample audit log entry if it doesn't exist
        \App\Models\AuditLog::firstOrCreate(
            [
                'school_id' => $school->id,
                'user_id' => $adminUser->id,
                'action' => 'system.seed',
            ],
            [
                'description' => 'Initial system seeding and setup',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'seeder-script',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Core data seeded successfully!');
        $this->command->info('Admin user: admin@dunco.com / password');
        $this->command->info('Total roles created: ' . count($roles));
    }
} 