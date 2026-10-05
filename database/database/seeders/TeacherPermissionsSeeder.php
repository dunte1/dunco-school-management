<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class TeacherPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Get the teacher role
        $teacherRole = Role::where('name', 'teacher')->first();
        
        if (!$teacherRole) {
            $this->command->error('Teacher role not found. Please run the TestDataSeeder first.');
            return;
        }

        // Define teacher-specific permissions
        $teacherPermissions = [
            // Dashboard access
            'dashboard.view',
            
            // Academic module permissions (full access for their classes)
            'academic.view',
            'academic.students.view',
            'academic.classes.view',
            'academic.subjects.view',
            'academic.grading.view',
            'academic.reports.view',
            
            // Examination module permissions (teacher-specific)
            'examination.view',
            'examination.teacher.exams',
            'examination.teacher.grade',
            'examination.teacher.analytics',
            'examination.results.view',
            'examination.schedules.view',
            
            // Timetable permissions (view only)
            'timetable.view',
            'timetable.teacher_availabilities.view',
            
            // Attendance permissions (mark and view for their classes)
            'attendance.view',
            'attendance.mark.view',
            'attendance.reports.view',
            
            // Communication permissions (limited)
            'communication.view',
            'communication.inbox.view',
            'communication.compose.view',
            
            // Portal access (student portal only)
            'portal.view',
            'portal.student.view',
            
            // Library permissions (basic access - view only)
            'library.view',
            'library.books.view',
            'library.borrows.view',
            
            // HR permissions (own records only)
            'hr.leave.view',
            'hr.leave.create',
            'hr.leave.edit',
            
            // Settings (basic settings only)
            'settings.view',
        ];

        // Create permissions if they don't exist
        foreach ($teacherPermissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                [
                    'display_name' => ucwords(str_replace('.', ' ', $permissionName)),
                    'module' => $this->getModuleFromPermission($permissionName),
                    'is_system' => true,
                ]
            );
        }

        // Get all teacher permissions
        $permissions = Permission::whereIn('name', $teacherPermissions)->get();
        
        // Assign permissions to teacher role
        $teacherRole->permissions()->sync($permissions->pluck('id')->toArray());

        // Update teacher user to have only teacher role
        $teacherUser = User::where('email', 'teacher@example.com')->first();
        if ($teacherUser) {
            // Remove all other roles and assign only teacher role
            $teacherUser->roles()->detach();
            $teacherUser->roles()->attach($teacherRole->id);
        }

        $this->command->info('Teacher permissions have been set up successfully!');
        $this->command->info('Teacher can now only access:');
        $this->command->info('- Dashboard');
        $this->command->info('- Academic (view only)');
        $this->command->info('- Examination (teacher tools)');
        $this->command->info('- Timetable (view only)');
        $this->command->info('- Attendance (mark and view)');
        $this->command->info('- Communication (limited)');
        $this->command->info('- Library (basic access)');
        $this->command->info('- HR (own records only)');
    }

    private function getModuleFromPermission($permissionName)
    {
        $parts = explode('.', $permissionName);
        return ucfirst($parts[0]);
    }
} 