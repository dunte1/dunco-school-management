<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class GrantAllPermissionsToAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the admin role
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Admin role not found!');
            return;
        }

        // Get all permissions
        $allPermissions = Permission::all();
        
        if ($allPermissions->isEmpty()) {
            $this->command->error('No permissions found!');
            return;
        }

        // Grant all permissions to admin role
        $adminRole->permissions()->sync($allPermissions->pluck('id'));
        
        $this->command->info("Granted {$allPermissions->count()} permissions to admin role");

        // Also ensure admin user has the admin role
        $adminUser = User::where('email', 'admin@dunco.com')->first();
        if ($adminUser) {
            $adminUser->roles()->sync([$adminRole->id]);
            $this->command->info('Admin user has admin role');
        }

        // Clear any cached permissions
        if (method_exists($adminUser, 'forgetCachedPermissions')) {
            $adminUser->forgetCachedPermissions();
        }
    }
} 