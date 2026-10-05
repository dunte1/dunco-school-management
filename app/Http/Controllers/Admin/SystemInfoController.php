<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SystemInfoController extends Controller
{
    public function index()
    {
        // Get system information
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database' => $this->getDatabaseInfo(),
            'server' => $this->getServerInfo(),
            'storage' => $this->getStorageInfo(),
            'cache' => $this->getCacheInfo(),
            'modules' => $this->getModulesInfo(),
            'environment' => $this->getEnvironmentInfo(),
        ];

        return view('admin.system.info', compact('systemInfo'));
    }

    private function getDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $databaseName = $connection->getDatabaseName();
            $driver = $connection->getDriverName();
            
            return [
                'name' => $databaseName,
                'driver' => $driver,
                'host' => config('database.connections.' . config('database.default') . '.host'),
                'port' => config('database.connections.' . config('database.default') . '.port'),
                'status' => 'Connected',
            ];
        } catch (\Exception $e) {
            return [
                'name' => 'Unknown',
                'driver' => 'Unknown',
                'host' => 'Unknown',
                'port' => 'Unknown',
                'status' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    private function getServerInfo()
    {
        return [
            'os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
    }

    private function getStorageInfo()
    {
        $disk = Storage::disk('local');
        $totalSpace = disk_total_space(storage_path());
        $freeSpace = disk_free_space(storage_path());
        $usedSpace = $totalSpace - $freeSpace;
        
        return [
            'total_space' => $this->formatBytes($totalSpace),
            'free_space' => $this->formatBytes($freeSpace),
            'used_space' => $this->formatBytes($usedSpace),
            'usage_percentage' => round(($usedSpace / $totalSpace) * 100, 2),
        ];
    }

    private function getCacheInfo()
    {
        $driver = config('cache.default');
        $prefix = config('cache.prefix');
        
        return [
            'driver' => $driver,
            'prefix' => $prefix,
            'status' => Cache::has('system_info_test') ? 'Working' : 'Unknown',
        ];
    }

    private function getModulesInfo()
    {
        $modules = [];
        $modulesPath = base_path('Modules');
        
        if (is_dir($modulesPath)) {
            $moduleDirs = scandir($modulesPath);
            foreach ($moduleDirs as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir($modulesPath . '/' . $dir)) {
                    $moduleJsonPath = $modulesPath . '/' . $dir . '/module.json';
                    if (file_exists($moduleJsonPath)) {
                        $moduleData = json_decode(file_get_contents($moduleJsonPath), true);
                        $modules[] = [
                            'name' => $moduleData['name'] ?? $dir,
                            'version' => $moduleData['version'] ?? 'Unknown',
                            'description' => $moduleData['description'] ?? 'No description',
                            'enabled' => $moduleData['enabled'] ?? true,
                        ];
                    }
                }
            }
        }
        
        return $modules;
    }

    private function getEnvironmentInfo()
    {
        return [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Yes' : 'No',
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ];
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
