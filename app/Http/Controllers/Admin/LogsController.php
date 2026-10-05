<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogsController extends Controller
{
    public function index()
    {
        $logFiles = $this->getLogFiles();
        $currentLog = request('log', 'laravel.log');
        $logContent = $this->getLogContent($currentLog);
        
        return view('admin.logs.index', compact('logFiles', 'currentLog', 'logContent'));
    }
    
    private function getLogFiles()
    {
        $logPath = storage_path('logs');
        $files = [];
        
        if (is_dir($logPath)) {
            $logFiles = File::files($logPath);
            foreach ($logFiles as $file) {
                if ($file->getExtension() === 'log') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
        }
        
        return $files;
    }
    
    private function getLogContent($filename)
    {
        $logPath = storage_path('logs/' . $filename);
        
        if (!File::exists($logPath)) {
            return 'Log file not found.';
        }
        
        $content = File::get($logPath);
        $lines = explode("\n", $content);
        $lines = array_reverse($lines); // Show newest first
        $lines = array_slice($lines, 0, 1000); // Limit to last 1000 lines
        
        return implode("\n", $lines);
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
