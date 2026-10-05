<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\School;
use App\Models\Role;
use App\Models\Permission;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\Class as AcademicClass;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\Exam;
use Modules\HR\Models\Staff;
use Modules\Finance\Models\Fee;
use Modules\Finance\Models\Payment;
use Modules\Library\Models\Book;
use Modules\Library\Models\Member;
use Modules\Hostel\Models\Room;
use Modules\Transport\Models\Vehicle;
use Carbon\Carbon;

class SystemHealthController extends Controller
{
    /**
     * Get comprehensive system health status
     */
    public function health(Request $request)
    {
        try {
            $schoolId = $request->get('school_id', auth()->user()->school_id ?? 1);
            
            $healthData = [
                'system' => $this->getSystemHealth(),
                'database' => $this->getDatabaseHealth(),
                'modules' => $this->getModulesHealth($schoolId),
                'performance' => $this->getPerformanceMetrics(),
                'security' => $this->getSecurityStatus(),
                'storage' => $this->getStorageStatus(),
                'api' => $this->getApiStatus(),
                'mobile_app' => $this->getMobileAppStatus(),
                'recommendations' => $this->getRecommendations(),
                'timestamp' => now()->toISOString(),
            ];

            $overallHealth = $this->calculateOverallHealth($healthData);

            return response()->json([
                'success' => true,
                'overall_health' => $overallHealth,
                'data' => $healthData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Health check failed: ' . $e->getMessage(),
                'overall_health' => 'critical'
            ], 500);
        }
    }

    /**
     * Get system-level health information
     */
    private function getSystemHealth()
    {
        return [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'uptime' => $this->getSystemUptime(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
        ];
    }

    /**
     * Get database health information
     */
    private function getDatabaseHealth()
    {
        try {
            $connection = DB::connection();
            $pdo = $connection->getPdo();
            
            $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
            $tableCount = count($tables);
            
            $totalRecords = 0;
            foreach ($tables as $table) {
                try {
                    $count = DB::table($table->name)->count();
                    $totalRecords += $count;
                } catch (\Exception $e) {
                    // Skip tables that can't be counted
                }
            }

            return [
                'connection' => 'healthy',
                'driver' => $connection->getDriverName(),
                'database' => $connection->getDatabaseName(),
                'tables' => $tableCount,
                'total_records' => $totalRecords,
                'last_query_time' => $connection->getQueryLog() ? end($connection->getQueryLog())['time'] : null,
                'connection_time' => $this->measureDatabaseConnectionTime(),
            ];
        } catch (\Exception $e) {
            return [
                'connection' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get modules health information
     */
    private function getModulesHealth($schoolId)
    {
        $modules = [
            'academic' => [
                'students' => Student::where('school_id', $schoolId)->count(),
                'classes' => AcademicClass::where('school_id', $schoolId)->count(),
                'subjects' => Subject::where('school_id', $schoolId)->count(),
                'exams' => Exam::where('school_id', $schoolId)->count(),
            ],
            'hr' => [
                'staff' => Staff::where('school_id', $schoolId)->count(),
            ],
            'finance' => [
                'fees' => Fee::where('school_id', $schoolId)->count(),
                'payments' => Payment::where('school_id', $schoolId)->count(),
            ],
            'library' => [
                'books' => Book::where('school_id', $schoolId)->count(),
                'members' => Member::where('school_id', $schoolId)->count(),
            ],
            'hostel' => [
                'rooms' => Room::where('school_id', $schoolId)->count(),
            ],
            'transport' => [
                'vehicles' => Vehicle::where('school_id', $schoolId)->count(),
            ],
            'users' => [
                'total_users' => User::where('school_id', $schoolId)->count(),
                'active_users' => User::where('school_id', $schoolId)->where('is_active', true)->count(),
                'roles' => Role::count(),
                'permissions' => Permission::count(),
            ],
        ];

        // Calculate module health scores
        foreach ($modules as $moduleName => &$moduleData) {
            $totalItems = array_sum($moduleData);
            $moduleData['health_score'] = $totalItems > 0 ? 'healthy' : 'warning';
            $moduleData['total_items'] = $totalItems;
        }

        return $modules;
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics()
    {
        return [
            'response_time' => $this->measureResponseTime(),
            'cache_hit_rate' => $this->getCacheHitRate(),
            'database_queries' => $this->getDatabaseQueryCount(),
            'memory_peak' => memory_get_peak_usage(true),
            'memory_current' => memory_get_usage(true),
            'execution_time' => microtime(true) - LARAVEL_START,
        ];
    }

    /**
     * Get security status
     */
    private function getSecurityStatus()
    {
        return [
            'csrf_protection' => config('session.csrf_protection', true),
            'https_enabled' => request()->isSecure(),
            'session_secure' => config('session.secure', false),
            'session_http_only' => config('session.http_only', true),
            'password_min_length' => config('auth.password_min_length', 8),
            'rate_limiting' => config('auth.throttle.enabled', true),
            'sanctum_enabled' => class_exists('Laravel\Sanctum\Sanctum'),
            'recent_security_events' => $this->getRecentSecurityEvents(),
        ];
    }

    /**
     * Get storage status
     */
    private function getStorageStatus()
    {
        $disk = Storage::disk('local');
        
        return [
            'local_storage' => [
                'available' => $disk->exists(''),
                'size' => $this->formatBytes(disk_free_space(storage_path())),
                'used' => $this->formatBytes(disk_total_space(storage_path()) - disk_free_space(storage_path())),
                'total' => $this->formatBytes(disk_total_space(storage_path())),
            ],
            'logs' => [
                'laravel_log' => Storage::exists('logs/laravel.log'),
                'log_size' => Storage::exists('logs/laravel.log') ? $this->formatBytes(Storage::size('logs/laravel.log')) : '0 B',
            ],
            'uploads' => [
                'directory_exists' => Storage::exists('uploads'),
                'file_count' => Storage::exists('uploads') ? count(Storage::files('uploads')) : 0,
            ],
        ];
    }

    /**
     * Get API status
     */
    private function getApiStatus()
    {
        return [
            'mobile_api' => [
                'health_endpoint' => $this->testEndpoint('/api/mobile/v1/health'),
                'auth_endpoint' => $this->testEndpoint('/api/mobile/v1/auth/login'),
                'version' => '1.0.0',
            ],
            'public_api' => [
                'v1_endpoint' => $this->testEndpoint('/api/v1'),
                'webhooks' => $this->testEndpoint('/api/v1/webhooks'),
            ],
            'rate_limiting' => [
                'enabled' => config('auth.throttle.enabled', true),
                'limits' => [
                    'api' => '120 requests per minute',
                    'mobile' => '120 requests per minute',
                ],
            ],
        ];
    }

    /**
     * Get mobile app status
     */
    private function getMobileAppStatus()
    {
        return [
            'android_app' => [
                'status' => 'ready',
                'version' => '1.0.0',
                'min_version' => '1.0.0',
                'force_update' => false,
                'features' => [
                    'authentication' => true,
                    'dashboard' => true,
                    'academics' => true,
                    'finance' => true,
                    'library' => true,
                    'attendance' => true,
                    'notifications' => true,
                    'biometric_auth' => true,
                    'qr_scanning' => true,
                    'offline_mode' => true,
                ],
            ],
            'api_integration' => [
                'base_url' => config('app.url') . '/api/mobile/v1',
                'endpoints' => [
                    'health' => '/health',
                    'login' => '/auth/login',
                    'logout' => '/auth/logout',
                    'profile' => '/me',
                    'dashboard' => '/dashboard',
                ],
            ],
        ];
    }

    /**
     * Get system recommendations
     */
    private function getRecommendations()
    {
        $recommendations = [];

        // Check for potential issues
        if (config('app.debug')) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Debug mode is enabled. Disable in production.',
                'priority' => 'high'
            ];
        }

        if (!request()->isSecure() && config('app.env') === 'production') {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'HTTPS is not enabled. Enable SSL in production.',
                'priority' => 'high'
            ];
        }

        if (memory_get_usage(true) > 128 * 1024 * 1024) { // 128MB
            $recommendations[] = [
                'type' => 'info',
                'message' => 'Memory usage is high. Consider optimizing.',
                'priority' => 'medium'
            ];
        }

        $diskUsage = (disk_total_space(storage_path()) - disk_free_space(storage_path())) / disk_total_space(storage_path()) * 100;
        if ($diskUsage > 80) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Disk usage is high (' . round($diskUsage, 1) . '%). Consider cleanup.',
                'priority' => 'medium'
            ];
        }

        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'success',
                'message' => 'System is running optimally.',
                'priority' => 'low'
            ];
        }

        return $recommendations;
    }

    /**
     * Calculate overall system health
     */
    private function calculateOverallHealth($healthData)
    {
        $scores = [];

        // System health
        $scores[] = $healthData['system']['maintenance_mode'] ? 0 : 100;

        // Database health
        $scores[] = $healthData['database']['connection'] === 'healthy' ? 100 : 0;

        // Module health
        $moduleScores = [];
        foreach ($healthData['modules'] as $module) {
            if (isset($module['health_score'])) {
                $moduleScores[] = $module['health_score'] === 'healthy' ? 100 : 50;
            }
        }
        $scores[] = !empty($moduleScores) ? array_sum($moduleScores) / count($moduleScores) : 100;

        // Performance
        $responseTime = $healthData['performance']['response_time'];
        $scores[] = $responseTime < 1000 ? 100 : ($responseTime < 3000 ? 75 : 50);

        // Security
        $securityScore = 0;
        if ($healthData['security']['csrf_protection']) $securityScore += 25;
        if ($healthData['security']['https_enabled']) $securityScore += 25;
        if ($healthData['security']['session_secure']) $securityScore += 25;
        if ($healthData['security']['rate_limiting']) $securityScore += 25;
        $scores[] = $securityScore;

        $averageScore = array_sum($scores) / count($scores);

        if ($averageScore >= 90) return 'excellent';
        if ($averageScore >= 75) return 'good';
        if ($averageScore >= 60) return 'fair';
        if ($averageScore >= 40) return 'poor';
        return 'critical';
    }

    // Helper methods
    private function getSystemUptime()
    {
        // This would require system-level access
        return 'Unknown';
    }

    private function getMemoryUsage()
    {
        return [
            'current' => $this->formatBytes(memory_get_usage(true)),
            'peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'limit' => ini_get('memory_limit'),
        ];
    }

    private function getDiskUsage()
    {
        $total = disk_total_space(storage_path());
        $free = disk_free_space(storage_path());
        $used = $total - $free;
        
        return [
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'total' => $this->formatBytes($total),
            'percentage' => round(($used / $total) * 100, 1),
        ];
    }

    private function measureDatabaseConnectionTime()
    {
        $start = microtime(true);
        try {
            DB::connection()->getPdo();
            return round((microtime(true) - $start) * 1000, 2); // milliseconds
        } catch (\Exception $e) {
            return null;
        }
    }

    private function measureResponseTime()
    {
        return round((microtime(true) - LARAVEL_START) * 1000, 2); // milliseconds
    }

    private function getCacheHitRate()
    {
        // This would require cache monitoring
        return 'Unknown';
    }

    private function getDatabaseQueryCount()
    {
        return count(DB::getQueryLog() ?? []);
    }

    private function getRecentSecurityEvents()
    {
        // This would query security logs
        return [];
    }

    private function testEndpoint($endpoint)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get(url($endpoint));
            return $response->successful() ? 'healthy' : 'unhealthy';
        } catch (\Exception $e) {
            return 'unreachable';
        }
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
