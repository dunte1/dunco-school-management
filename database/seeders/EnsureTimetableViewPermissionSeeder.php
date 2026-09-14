<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnsureTimetableViewPermissionSeeder extends Seeder
{
    public function run()
    {
        // Ensure the permission exists
        $permission = DB::table('permissions')->where('name', 'timetable.view')->first();
        if (!$permission) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => 'timetable.view',
                'display_name' => 'View Timetable',
                'module' => 'Timetable',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $permissionId = $permission->id;
        }
        // Assign to the admin role (role name is 'admin'; 'System Administrator'
        // is only its display name). Uses the app's custom permission_role pivot.
        $role = DB::table('roles')->where('name', 'admin')->first();
        if ($role && $permissionId) {
            DB::table('permission_role')->updateOrInsert(
                [
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                    'school_id' => null,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
} 