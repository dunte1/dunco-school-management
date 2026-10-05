<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SystemController extends Controller
{
    use ApiResponse;

    public function getStatus(Request $request)
    {
        try {
            $status = [
                'status' => 'online',
                'uptime' => '99.9%',
                'version' => '1.0.0',
                'last_updated' => now()->toISOString(),
                'services' => [
                    'database' => 'online',
                    'cache' => 'online',
                    'queue' => 'online',
                    'websocket' => 'online'
                ]
            ];
            return $this->successResponse($status, 'System status retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting system status: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve system status.', 500);
        }
    }

    public function getVersion(Request $request)
    {
        try {
            $version = [
                'app_version' => '1.0.0',
                'api_version' => 'v1',
                'build_date' => '2024-01-15',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version()
            ];
            return $this->successResponse($version, 'Version information retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting version: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve version information.', 500);
        }
    }

    public function getDeviceInfo(Request $request)
    {
        try {
            $deviceInfo = [
                'device_id' => $request->header('X-Device-ID', 'unknown'),
                'platform' => $request->header('X-Platform', 'unknown'),
                'app_version' => $request->header('X-App-Version', '1.0.0'),
                'os_version' => $request->header('X-OS-Version', 'unknown'),
                'last_seen' => now()->toISOString()
            ];
            return $this->successResponse($deviceInfo, 'Device information retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting device info: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve device information.', 500);
        }
    }
}
