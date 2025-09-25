<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create roles if not exist
        $roles = [
            'admin', 'teacher', 'student', 'parent', 'staff', 'finance', 'librarian', 'warden'
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 2. Create users for each role
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Teacher User', 'email' => 'teacher@example.com', 'role' => 'teacher'],
            ['name' => 'Student User', 'email' => 'student@example.com', 'role' => 'student'],
            ['name' => 'Parent User', 'email' => 'parent@example.com', 'role' => 'parent'],
            ['name' => 'Staff User', 'email' => 'staff@example.com', 'role' => 'staff'],
            ['name' => 'Finance User', 'email' => 'finance@example.com', 'role' => 'finance'],
            ['name' => 'Librarian User', 'email' => 'librarian@example.com', 'role' => 'librarian'],
            ['name' => 'Warden User', 'email' => 'warden@example.com', 'role' => 'warden'],
        ];
        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            
            // Get the role and assign it to the user
            $role = Role::where('name', $u['role'])->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }

        // 3. Create a test school
        $schoolModel = class_exists('Modules\\Core\\Models\\School') ? 'Modules\\Core\\Models\\School' : (class_exists('App\\Models\\School') ? 'App\\Models\\School' : null);
        if ($schoolModel) {
            $school = $schoolModel::firstOrCreate([
                'name' => 'Test School',
                'code' => 'TESTSCH',
            ], [
                'address' => '123 Test Lane',
                'motto' => 'Excellence for All',
                'domain' => 'testschool.edu',
                'level' => 'Secondary',
                'phone' => '0700000000',
                'email' => 'info@testschool.edu',
                'logo' => null,
                'is_active' => true,
            ]);
            $schoolId = $school->id;
        } else {
            $schoolId = 1; // fallback if no school model
        }

        // 4. Sample Academic Data
        $class = \Modules\Academic\Models\AcademicClass::firstOrCreate([
            'name' => 'Test Class',
            'code' => 'CLASS1',
            'school_id' => $schoolId,
            'description' => 'Test class for demo',
            'capacity' => 40,
            'is_active' => true,
            'academic_year' => date('Y'),
        ]);
        $subject = \Modules\Academic\Models\Subject::firstOrCreate([
            'name' => 'Mathematics',
            'code' => 'MATH1',
            'school_id' => $schoolId,
            'description' => 'Mathematics subject',
            'credits' => 3,
            'is_active' => true,
        ]);
        $student = \Modules\Academic\Models\Student::firstOrCreate([
            'student_id' => 'STU001',
        ], [
            'user_id' => User::where('email', 'student@example.com')->first()->id,
            'class_id' => $class->id,
            'school_id' => $schoolId,
            'admission_number' => 'ADM001',
            'name' => 'Student User',
            'admission_date' => now()->subYear(),
            'date_of_birth' => now()->subYears(16),
            'gender' => 'male',
            'is_active' => true,
        ]);
        $teacher = User::where('email', 'teacher@example.com')->first();
        if (method_exists($subject, 'teachers')) {
            $subject->teachers()->syncWithoutDetaching([$teacher->id]);
        }

        // 5. Sample Hostel Data
        $hostel = \Modules\Hostel\Models\Hostel::firstOrCreate([
            'name' => 'Test Hostel',
            'school_id' => $schoolId,
        ]);
        $floor = \Modules\Hostel\Models\Floor::firstOrCreate([
            'hostel_id' => $hostel->id,
            'name' => 'Ground Floor',
        ]);
        $room = \Modules\Hostel\Models\Room::firstOrCreate([
            'hostel_id' => $hostel->id,
            'floor_id' => $floor->id,
            'name' => 'Room 101',
            'type' => 'single',
            'capacity' => 1,
        ]);
        $warden = User::where('email', 'warden@example.com')->first();
        // Note: wardens() is hasMany, not belongsToMany, so we can't use syncWithoutDetaching
        // The warden relationship should be handled differently if needed
        // Create a bed first, then allocate it
        $bed = \Modules\Hostel\Models\Bed::firstOrCreate([
            'room_id' => $room->id,
            'bed_number' => '1',
            'status' => 'available',
        ]);
        \Modules\Hostel\Models\RoomAllocation::firstOrCreate([
            'student_id' => $student->id,
            'bed_id' => $bed->id,
            'status' => 'active',
        ]);

        // 6. Sample Finance Data
        $fee = \Modules\Finance\Models\Fee::firstOrCreate([
            'name' => 'Tuition Fee',
            'amount' => 1000,
        ]);
        $invoice = \Modules\Finance\Models\Invoice::firstOrCreate([
            'student_id' => $student->id,
            'total_amount' => 1000,
            'status' => 'unpaid',
            'due_date' => now()->addMonth(),
        ]);
        \Modules\Finance\Models\Payment::firstOrCreate([
            'invoice_id' => $invoice->id,
            'amount' => 1000,
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        // 7. Sample Library Data - Skipped for now due to complex relationships

        // 8. Sample Attendance Data - Skipped due to unique constraint issues

        // 9. Sample HR Data
        $department = \Modules\HR\Models\Department::firstOrCreate([
            'name' => 'Science',
            'school_id' => $schoolId,
        ]);
        $staff = \Modules\HR\Models\Staff::firstOrCreate([
            'staff_id' => 'STAFF001',
        ], [
            'user_id' => User::where('email', 'staff@example.com')->first()->id,
            'department_id' => $department->id,
            'school_id' => $schoolId,
            'first_name' => 'Staff',
            'last_name' => 'User',
            'email' => 'staff@example.com',
            'phone' => '0700000001',
            'gender' => 'male',
            'job_title' => 'General Staff',
            'status' => 'active',
        ]);
        \Modules\HR\Models\Leave::firstOrCreate([
            'staff_id' => $staff->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'days' => 6, // 5 days difference + 1 (inclusive)
            'status' => 'pending',
            'type' => 'annual',
        ]);

        // 10. Parent-Student Link
        $parent = User::where('email', 'parent@example.com')->first();
        if (method_exists($student, 'parents')) {
            $student->parents()->syncWithoutDetaching([$parent->id => ['relationship' => 'Parent', 'is_primary' => true]]);
        }
    }
}
