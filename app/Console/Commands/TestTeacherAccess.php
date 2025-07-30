<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helpers\NavigationHelper;

class TestTeacherAccess extends Command
{
    protected $signature = 'test:teacher-access';
    protected $description = 'Test what modules teacher can access';

    public function handle()
    {
        $teacher = User::where('email', 'teacher@example.com')->first();
        
        if (!$teacher) {
            $this->error('Teacher user not found!');
            return;
        }

        // Simulate login
        auth()->login($teacher);

        $this->info('Testing teacher access for: ' . $teacher->name);
        $this->info('User permissions: ' . $teacher->getAllPermissionNames()->implode(', '));

        $modules = [
            'core', 'academic', 'examination', 'finance', 'hr', 'library', 
            'hostel', 'transport', 'timetable', 'attendance', 'communication', 
            'portal', 'lms', 'document', 'notification', 'settings', 'api', 
            'analytics', 'chatbot', 'marketplace', 'pwa', 'research', 
            'alumni', 'localization'
        ];

        $this->info('Module access:');
        foreach ($modules as $module) {
            $canAccess = NavigationHelper::canAccessModule($module);
            $status = $canAccess ? '✓' : '✗';
            $this->line("  {$status} {$module}");
        }

        $userModules = NavigationHelper::getUserModules();
        $this->info('Available modules: ' . implode(', ', $userModules));
    }
} 