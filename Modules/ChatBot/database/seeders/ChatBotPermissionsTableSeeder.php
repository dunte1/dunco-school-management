<?php

namespace Modules\ChatBot\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChatBotPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if permissions table exists
        if (!Schema::hasTable('permissions')) {
            return;
        }

        // Define chatbot permissions
        $permissions = [
            [
                'name' => 'chatbot.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
                'description' => 'View chatbot interface'
            ],
            [
                'name' => 'chatbot.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
                'description' => 'Create chatbot conversations'
            ],
            [
                'name' => 'chatbot.edit',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
                'description' => 'Edit chatbot conversations'
            ],
            [
                'name' => 'chatbot.delete',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
                'description' => 'Delete chatbot conversations'
            ],
            [
                'name' => 'chatbot.admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
                'description' => 'Manage chatbot settings and administration'
            ],
            [
                'name' => 'chatbot.export',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
                'description' => 'Export chatbot conversations'
            ],
            [
                'name' => 'chatbot.analytics',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
                'description' => 'View chatbot analytics and statistics'
            ]
        ];

        // Insert permissions
        foreach ($permissions as $permission) {
            // Check if permission already exists
            $exists = DB::table('permissions')
                ->where('name', $permission['name'])
                ->where('guard_name', $permission['guard_name'])
                ->exists();

            if (!$exists) {
                DB::table('permissions')->insert($permission);
            }
        }

        // Assign default permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Assign permissions to default roles
     *
     * @return void
     */
    private function assignPermissionsToRoles()
    {
        // Check if roles and role_has_permissions tables exist
        if (!Schema::hasTable('roles') || !Schema::hasTable('role_has_permissions')) {
            return;
        }

        // Get role IDs
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        $teacherRole = DB::table('roles')->where('name', 'teacher')->first();
        $studentRole = DB::table('roles')->where('name', 'student')->first();
        $parentRole = DB::table('roles')->where('name', 'parent')->first();

        // Get permission IDs
        $viewPermission = DB::table('permissions')->where('name', 'chatbot.view')->first();
        $createPermission = DB::table('permissions')->where('name', 'chatbot.create')->first();
        $editPermission = DB::table('permissions')->where('name', 'chatbot.edit')->first();
        $deletePermission = DB::table('permissions')->where('name', 'chatbot.delete')->first();
        $adminPermission = DB::table('permissions')->where('name', 'chatbot.admin')->first();
        $exportPermission = DB::table('permissions')->where('name', 'chatbot.export')->first();
        $analyticsPermission = DB::table('permissions')->where('name', 'chatbot.analytics')->first();

        // Assign permissions to admin role (all permissions)
        if ($adminRole && $adminPermission) {
            $this->assignPermissionToRole($adminPermission->id, $adminRole->id);
        }

        if ($adminRole && $viewPermission) {
            $this->assignPermissionToRole($viewPermission->id, $adminRole->id);
        }

        if ($adminRole && $createPermission) {
            $this->assignPermissionToRole($createPermission->id, $adminRole->id);
        }

        if ($adminRole && $editPermission) {
            $this->assignPermissionToRole($editPermission->id, $adminRole->id);
        }

        if ($adminRole && $deletePermission) {
            $this->assignPermissionToRole($deletePermission->id, $adminRole->id);
        }

        if ($adminRole && $exportPermission) {
            $this->assignPermissionToRole($exportPermission->id, $adminRole->id);
        }

        if ($adminRole && $analyticsPermission) {
            $this->assignPermissionToRole($analyticsPermission->id, $adminRole->id);
        }

        // Assign permissions to teacher role
        if ($teacherRole && $viewPermission) {
            $this->assignPermissionToRole($viewPermission->id, $teacherRole->id);
        }

        if ($teacherRole && $createPermission) {
            $this->assignPermissionToRole($createPermission->id, $teacherRole->id);
        }

        if ($teacherRole && $editPermission) {
            $this->assignPermissionToRole($editPermission->id, $teacherRole->id);
        }

        if ($teacherRole && $deletePermission) {
            $this->assignPermissionToRole($deletePermission->id, $teacherRole->id);
        }

        // Assign permissions to student role
        if ($studentRole && $viewPermission) {
            $this->assignPermissionToRole($viewPermission->id, $studentRole->id);
        }

        if ($studentRole && $createPermission) {
            $this->assignPermissionToRole($createPermission->id, $studentRole->id);
        }

        if ($studentRole && $editPermission) {
            $this->assignPermissionToRole($editPermission->id, $studentRole->id);
        }

        if ($studentRole && $deletePermission) {
            $this->assignPermissionToRole($deletePermission->id, $studentRole->id);
        }

        // Assign permissions to parent role
        if ($parentRole && $viewPermission) {
            $this->assignPermissionToRole($viewPermission->id, $parentRole->id);
        }

        if ($parentRole && $createPermission) {
            $this->assignPermissionToRole($createPermission->id, $parentRole->id);
        }

        if ($parentRole && $editPermission) {
            $this->assignPermissionToRole($editPermission->id, $parentRole->id);
        }

        if ($parentRole && $deletePermission) {
            $this->assignPermissionToRole($deletePermission->id, $parentRole->id);
        }
    }

    /**
     * Assign a permission to a role
     *
     * @param int $permissionId
     * @param int $roleId
     * @return void
     */
    private function assignPermissionToRole($permissionId, $roleId)
    {
        // Check if the assignment already exists
        $exists = DB::table('role_has_permissions')
            ->where('permission_id', $permissionId)
            ->where('role_id', $roleId)
            ->exists();

        if (!$exists) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionId,
                'role_id' => $roleId,
            ]);
        }
    }
}