<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GrantAllPermissionsToSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure super_admin role exists
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['display_name' => 'Super Admin', 'description' => 'Platform-wide super administrator', 'is_system' => true]
        );

        // Fetch all permissions
        $allPermissions = Permission::all(['id']);
        if ($allPermissions->isEmpty()) {
            $this->command?->warn('No permissions found. Run SystemPermissionsSeeder first if needed.');
        } else {
            // Grant all permissions to super_admin
            $superAdminRole->permissions()->sync($allPermissions->pluck('id'));
            $this->command?->info("Granted {$allPermissions->count()} permissions to super_admin role");
        }

        // Attach super_admin role to default super admin user if present
        $user = User::where('email', env('SUPER_ADMIN_EMAIL', 'superadmin@dunco.com'))->first();
        if ($user) {
            // Ensure pivot table exists before attaching
            if (DB::getSchemaBuilder()->hasTable('role_user')) {
                if (!$user->roles()->where('roles.id', $superAdminRole->id)->exists()) {
                    // Try attach with nullable school_id
                    $user->roles()->attach($superAdminRole->id, ['school_id' => null]);
                    $this->command?->info('Attached super_admin role to super admin user');
                } else {
                    $this->command?->line('Super admin user already has super_admin role');
                }
            } else {
                $this->command?->warn("'role_user' pivot table not found; skipping role attachment to user.");
            }
        } else {
            $this->command?->warn('Super admin user not found; skipping user-role attachment.');
        }
    }
}
