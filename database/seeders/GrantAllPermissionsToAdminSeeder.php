<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrantAllPermissionsToAdminSeeder extends Seeder
{
    public function run()
    {
        $role = DB::table('roles')->where('name', 'System Administrator')->first();
        $permissions = DB::table('permissions')->pluck('id');
        if ($role) {
            foreach ($permissions as $permissionId) {
                DB::table('role_has_permissions')->updateOrInsert([
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }
} 