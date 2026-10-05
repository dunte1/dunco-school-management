<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class TestPerformance extends Command
{
    protected $signature = 'test:performance';
    protected $description = 'Test system performance and response times';

    public function handle()
    {
        $this->info('Testing System Performance...');
        
        // Test database connection
        $this->testDatabasePerformance();
        
        // Test cache performance
        $this->testCachePerformance();
        
        // Test queue performance
        $this->testQueuePerformance();
        
        // Test file system performance
        $this->testFileSystemPerformance();
        
        $this->info('Performance tests completed!');
    }
    
    private function testDatabasePerformance()
    {
        $this->info('Testing Database Performance...');
        
        $start = microtime(true);
        $users = DB::table('users')->count();
        $dbTime = (microtime(true) - $start) * 1000;
        
        $this->line("Database query time: {$dbTime}ms");
        
        if ($dbTime < 100) {
            $this->info('✓ Database performance: EXCELLENT');
        } elseif ($dbTime < 500) {
            $this->info('✓ Database performance: GOOD');
        } else {
            $this->warn('⚠ Database performance: SLOW');
        }
    }
    
    private function testCachePerformance()
    {
        $this->info('Testing Cache Performance...');
        
        $start = microtime(true);
        Cache::put('test_key', 'test_value', 60);
        $value = Cache::get('test_key');
        $cacheTime = (microtime(true) - $start) * 1000;
        
        $this->line("Cache operation time: {$cacheTime}ms");
        
        if ($cacheTime < 10) {
            $this->info('✓ Cache performance: EXCELLENT');
        } elseif ($cacheTime < 50) {
            $this->info('✓ Cache performance: GOOD');
        } else {
            $this->warn('⚠ Cache performance: SLOW');
        }
    }
    
    private function testQueuePerformance()
    {
        $this->info('Testing Queue Performance...');
        
        $start = microtime(true);
        $failedJobs = DB::table('failed_jobs')->count();
        $queueTime = (microtime(true) - $start) * 1000;
        
        $this->line("Queue check time: {$queueTime}ms");
        $this->line("Failed jobs: {$failedJobs}");
        
        if ($failedJobs == 0) {
            $this->info('✓ Queue status: HEALTHY');
        } else {
            $this->warn("⚠ Queue has {$failedJobs} failed jobs");
        }
    }
    
    private function testFileSystemPerformance()
    {
        $this->info('Testing File System Performance...');
        
        $start = microtime(true);
        $storagePath = storage_path('app');
        $files = count(glob($storagePath . '/*'));
        $fsTime = (microtime(true) - $start) * 1000;
        
        $this->line("File system check time: {$fsTime}ms");
        $this->line("Storage files: {$files}");
        
        if ($fsTime < 100) {
            $this->info('✓ File system performance: EXCELLENT');
        } elseif ($fsTime < 500) {
            $this->info('✓ File system performance: GOOD');
        } else {
            $this->warn('⚠ File system performance: SLOW');
        }
    }
}
