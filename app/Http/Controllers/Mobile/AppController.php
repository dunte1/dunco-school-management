<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    use ApiResponse;

    public function verifyApp(Request $request)
    {
        try {
            // Get app verification data
            $appData = [
                'status' => '200',
                'site_url' => config('app.url') . '/',
                'app_ver' => '4.2',
                'app_logo' => 'logo.png',
                'app_primary_color_code' => '#2e4b5f',
                'app_secondary_color_code' => '#daf6fc',
                'school_name' => 'Dunco School Management System',
                'school_address' => 'Nairobi, Kenya',
                'school_phone' => '+254 700 000 000',
                'school_email' => 'info@duncowebsolutions.co.ke',
                'date_format' => 'd-m-Y',
                'time_format' => 'H:i',
                'currency_symbol' => 'KSh',
                'currency_name' => 'Kenyan Shilling',
                'timezone' => 'Africa/Nairobi',
                'language' => 'en',
                'maintenance_mode' => false,
                'app_status' => 'active'
            ];

            return response()->json($appData, 200);
        } catch (\Exception $e) {
            Log::error("Error verifying app: " . $e->getMessage());
            return $this->errorResponse('App verification failed.', 500);
        }
    }

    public function getAppSettings(Request $request)
    {
        try {
            $settings = [
                'status' => '200',
                'site_url' => config('app.url') . '/',
                'app_ver' => '4.2',
                'app_logo' => 'logo.png',
                'app_primary_color_code' => '#2e4b5f',
                'app_secondary_color_code' => '#daf6fc',
                'school_name' => 'Dunco School Management System',
                'school_address' => 'Nairobi, Kenya',
                'school_phone' => '+254 700 000 000',
                'school_email' => 'info@duncowebsolutions.co.ke',
                'date_format' => 'd-m-Y',
                'time_format' => 'H:i',
                'currency_symbol' => 'KSh',
                'currency_name' => 'Kenyan Shilling',
                'timezone' => 'Africa/Nairobi',
                'language' => 'en',
                'maintenance_mode' => false,
                'app_status' => 'active',
                'features' => [
                    'elearning' => true,
                    'communicate' => true,
                    'academics' => true,
                    'finance' => true,
                    'transport' => true,
                    'hostel' => true,
                    'library' => true,
                    'attendance' => true,
                    'exams' => true,
                    'assignments' => true,
                    'timetable' => true,
                    'notifications' => true,
                    'ai_assistant' => true,
                    'offline_sync' => true,
                    'payment_gateway' => true
                ]
            ];

            return response()->json($settings, 200);
        } catch (\Exception $e) {
            Log::error("Error getting app settings: " . $e->getMessage());
            return $this->errorResponse('Failed to get app settings.', 500);
        }
    }
}
