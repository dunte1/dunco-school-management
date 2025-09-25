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
        // Assign to System Administrator
        $role = DB::table('roles')->where('name', 'System Administrator')->first();
        if ($role && $permissionId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'role_id' => $role->id,
                'permission_id' => $permissionId,
            ]);
        }
    }
} 