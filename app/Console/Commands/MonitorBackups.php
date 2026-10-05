<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MonitorBackups extends Command
{
    protected $signature = 'app:backup-monitor {--disk= : Filesystem disk where backups are stored}';

    protected $description = 'Simple backup monitor: checks latest backup age and presence';

    public function handle(): int
    {
        $backupDiskName = $this->option('disk') ?: (env('BACKUP_DISK') ?: config('filesystems.default', 'local'));
        $disk = Storage::disk($backupDiskName);

        $dirs = collect($disk->allDirectories('backups'));
        if ($dirs->isEmpty()) {
            $this->error('No backups found under backups/');
            return self::FAILURE;
        }

        $files = collect($disk->allFiles('backups'));
        if ($files->isEmpty()) {
            $this->error('No backup files found under backups/');
            return self::FAILURE;
        }

        $latest = $files->map(function ($path) use ($disk) {
                return [
                    'path' => $path,
                    'timestamp' => $disk->lastModified($path),
                ];
            })
            ->sortByDesc('timestamp')
            ->first();

        if (!$latest) {
            $this->error('Unable to determine latest backup');
            return self::FAILURE;
        }

        $ageMinutes = (time() - (int) $latest['timestamp']) / 60;
        $this->info('Latest backup: '.$latest['path'].' (age: '.round($ageMinutes).' minutes)');

        // Consider older than 36 hours as unhealthy
        if ($ageMinutes > 36 * 60) {
            $this->warn('Backups are older than 36 hours.');
            return self::FAILURE;
        }

        $this->info('Backup health OK');
        return self::SUCCESS;
    }
}




























