<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\User;

class CheckTeacherPermissions extends Command
{
    protected $signature = 'check:teacher-permissions';
    protected $description = 'Check teacher role permissions';

    public function handle()
    {
        $role = Role::where('name', 'teacher')->first();
        
        if (!$role) {
            $this->error('Teacher role not found!');
            return;
        }

        $this->info('Teacher role found: ' . $role->name);
        $this->info('Permissions count: ' . $role->permissions->count());
        
        if ($role->permissions->count() > 0) {
            $this->info('Permissions:');
            foreach ($role->permissions as $permission) {
                $this->line('- ' . $permission->name . ' (' . $permission->module . ')');
            }
        } else {
            $this->warn('No permissions assigned to teacher role!');
        }

        // Check teacher user
        $teacherUser = User::where('email', 'teacher@example.com')->first();
        if ($teacherUser) {
            $this->info('Teacher user found: ' . $teacherUser->name);
            $this->info('User roles: ' . $teacherUser->roles->pluck('name')->implode(', '));
        } else {
            $this->error('Teacher user not found!');
        }
    }
} 