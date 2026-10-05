<?php

namespace App\Services\Backup;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupService
{
    private string $diskName;
    private int $retentionDays;
    private ?string $encryptionKey;
    private array $notifyEmails;

    public function __construct()
    {
        $this->diskName = config('backup_module.default_disk');
        $this->retentionDays = (int) config('backup_module.retention_days');
        $this->encryptionKey = config('backup_module.encryption_key');
        $this->notifyEmails = (array) config('backup_module.notify_emails', []);
    }

    public function listBackups(): array
    {
        $disk = Storage::disk($this->diskName);
        $files = $disk->allFiles('backups');
        $mapped = [];
        foreach ($files as $file) {
            $mapped[] = [
                'path' => $file,
                'size' => $disk->size($file),
                'modified' => $disk->lastModified($file),
            ];
        }
        usort($mapped, fn($a, $b) => $b['modified'] <=> $a['modified']);
        return $mapped;
    }

    public function runBackup(string $type, array $modules = [], ?string $diskOverride = null, bool $encrypt = false): bool
    {
        $diskName = $diskOverride ?: $this->diskName;
        $disk = Storage::disk($diskName);
        $timestamp = now()->format('Ymd_His');
        $baseDir = 'backups/'.now()->format('Y/m/d');
        $disk->makeDirectory($baseDir);

        try {
            // Database dump
            $dbDump = app(\App\Console\Commands\RunAppBackup::class);
            $reflection = new \ReflectionClass($dbDump);
            $method = $reflection->getMethod('dumpDatabase');
            $method->setAccessible(true);
            $sql = $method->invoke($dbDump);
            if ($sql !== null) {
                $dbPath = $baseDir.'/db_'.$timestamp.'.sql'.($encrypt ? '.enc' : '');
                $payload = $encrypt ? $this->encryptData($sql) : $sql;
                $disk->put($dbPath, $payload);
            }

            // Files zip
            $zipper = app(\App\Console\Commands\RunAppBackup::class);
            $method2 = (new \ReflectionClass($zipper))->getMethod('zipStorage');
            $method2->setAccessible(true);
            $zip = $method2->invoke($zipper, storage_path('app'));
            if ($zip !== null) {
                $filesPath = $baseDir.'/storage_'.$timestamp.'.zip'.($encrypt ? '.enc' : '');
                $payload = $encrypt ? $this->encryptData($zip) : $zip;
                $disk->put($filesPath, $payload);
            }

            $this->applyRetention($disk);
            $this->notify('Backup completed', compact('baseDir', 'diskName'));
            return true;
        } catch (\Throwable $e) {
            Log::error('Backup failed', ['error' => $e->getMessage()]);
            $this->notify('Backup failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function streamBackup(string $path): StreamedResponse
    {
        $disk = Storage::disk($this->diskName);
        abort_unless($disk->exists($path), 404);
        return new StreamedResponse(function () use ($disk, $path) {
            echo $disk->get($path);
        }, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.basename($path).'"',
        ]);
    }

    public function deleteBackup(string $path): void
    {
        $disk = Storage::disk($this->diskName);
        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }

    public function restore(string $path, string $mode, array $modules = []): void
    {
        // Stub: implement DB import and selective file restore.
        Log::info('Restore requested', compact('path', 'mode', 'modules'));
    }

    private function encryptData(string $data): string
    {
        $key = $this->encryptionKey;
        if (!$key) {
            return $data;
        }
        return openssl_encrypt($data, 'AES-256-CBC', substr(hash('sha256', $key, true), 0, 32), 0, substr(hash('sha256', $key), 0, 16)) ?: $data;
    }

    private function applyRetention($disk): void
    {
        if ($this->retentionDays <= 0) {
            return;
        }
        $threshold = now()->subDays($this->retentionDays)->timestamp;
        foreach ($disk->allFiles('backups') as $file) {
            if ($disk->lastModified($file) < $threshold) {
                $disk->delete($file);
            }
        }
    }

    private function notify(string $subject, array $payload = []): void
    {
        try {
            foreach ($this->notifyEmails as $recipient) {
                Mail::raw($subject.' '.json_encode($payload), function ($m) use ($recipient, $subject) {
                    $m->to($recipient)->subject('[Backup] '.$subject);
                });
            }
        } catch (\Throwable $e) {
            Log::error('Backup notification failed', ['error' => $e->getMessage()]);
        }
    }
}


















