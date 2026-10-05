<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BackupPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'backups.view',
            'backups.create',
            'backups.download',
            'backups.delete',
            'backups.restore',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        if ($admin = Role::where('name', 'admin')->first()) {
            $admin->givePermissionTo($permissions);
            $this->command?->info('Backup permissions assigned to admin role');
        } else {
            $this->command?->warn('Admin role not found; create it before assigning backup permissions.');
        }
    }
}




























