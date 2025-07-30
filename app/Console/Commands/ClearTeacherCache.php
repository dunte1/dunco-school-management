<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ClearTeacherCache extends Command
{
    protected $signature = 'clear:teacher-cache';
    protected $description = 'Clear teacher permission cache';

    public function handle()
    {
        $teacher = User::where('email', 'teacher@example.com')->first();
        
        if (!$teacher) {
            $this->error('Teacher user not found!');
            return;
        }

        $teacher->clearPermissionCache();
        $this->info('Teacher permission cache cleared successfully!');
        
        // Also clear application cache
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');
        
        $this->info('All caches cleared!');
        $this->info('Teacher should now see updated sidebar. Please log out and log back in.');
    }
} 