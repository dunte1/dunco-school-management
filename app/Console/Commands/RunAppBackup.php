<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RunAppBackup extends Command
{
    protected $signature = 'app:backup {--disk= : Filesystem disk to store backups (default from BACKUP_DISK or filesystems.default)}';

    protected $description = 'Create application backup: database dump and storage archive';

    public function handle(): int
    {
        $timestamp = now()->format('Ymd_His');
        $backupDiskName = $this->option('disk') ?: (env('BACKUP_DISK') ?: config('filesystems.default', 'local'));
        $disk = Storage::disk($backupDiskName);

        $backupBasePath = 'backups/'.now()->format('Y/m/d');
        $dbFilename = "db_{$timestamp}.sql";
        $storageFilename = "storage_{$timestamp}.zip";

        // Ensure directory
        $disk->makeDirectory($backupBasePath);

        $dbDump = $this->dumpDatabase();
        if ($dbDump !== null) {
            $disk->put($backupBasePath.'/'.$dbFilename, $dbDump);
            $this->info("Database dump saved: {$backupBasePath}/{$dbFilename}");
        } else {
            $this->warn('Database dump skipped or failed. Check logs.');
        }

        $zipData = $this->zipStorage(app_path: storage_path('app'));
        if ($zipData !== null) {
            $disk->put($backupBasePath.'/'.$storageFilename, $zipData);
            $this->info("Storage archive saved: {$backupBasePath}/{$storageFilename}");
        } else {
            $this->warn('Storage archive failed. Check logs.');
        }

        return self::SUCCESS;
    }

    private function dumpDatabase(): ?string
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        try {
            if (($config['driver'] ?? null) === 'mysql') {
                $host = $config['host'] ?? '127.0.0.1';
                $port = (string) ($config['port'] ?? '3306');
                $database = $config['database'] ?? '';
                $username = $config['username'] ?? '';
                $password = $config['password'] ?? '';

                // Build mysqldump command
                $cmd = [
                    'mysqldump',
                    "-h{$host}",
                    "-P{$port}",
                    "-u{$username}",
                    $database,
                    '--single-transaction',
                    '--quick',
                    '--lock-tables=false',
                ];

                $env = [];
                if ($password !== '') {
                    $env['MYSQL_PWD'] = $password;
                }

                $process = proc_open(implode(' ', array_map('escapeshellarg', $cmd)), [
                    1 => ['pipe', 'w'],
                    2 => ['pipe', 'w'],
                ], $pipes, null, $env);

                if (\is_resource($process)) {
                    $output = stream_get_contents($pipes[1]);
                    $error = stream_get_contents($pipes[2]);
                    fclose($pipes[1]);
                    fclose($pipes[2]);
                    $exitCode = proc_close($process);

                    if ($exitCode === 0) {
                        return $output;
                    }

                    Log::error('mysqldump failed', ['exit' => $exitCode, 'error' => $error]);
                    return null;
                }

                Log::error('Failed to start mysqldump process');
                return null;
            }

            if (($config['driver'] ?? null) === 'sqlite') {
                $path = $config['database'] ?? database_path('database.sqlite');
                if (file_exists($path)) {
                    return file_get_contents($path) ?: null;
                }
                Log::warning('SQLite database file not found', ['path' => $path]);
                return null;
            }

            Log::warning('Unsupported DB driver for backup', ['driver' => $config['driver'] ?? null]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Database dump error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function zipStorage(string $app_path): ?string
    {
        try {
            $zip = new \ZipArchive();
            $tmp = storage_path('framework/cache/'.Str::uuid().'.zip');
            if ($zip->open($tmp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                return null;
            }

            $rootPath = realpath($app_path);
            if ($rootPath === false) {
                return null;
            }

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($rootPath, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                $filePath = realpath((string) $file);
                if ($filePath === false) {
                    continue;
                }
                $relativePath = ltrim(str_replace($rootPath, '', $filePath), DIRECTORY_SEPARATOR);

                if (is_dir($filePath)) {
                    $zip->addEmptyDir($relativePath);
                } else {
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
            $data = file_get_contents($tmp) ?: null;
            @unlink($tmp);
            return $data;
        } catch (\Throwable $e) {
            Log::error('Storage zip error', ['error' => $e->getMessage()]);
            return null;
        }
    }
}












