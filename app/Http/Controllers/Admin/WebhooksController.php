<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhooksController extends Controller
{
    public function index()
    {
        $webhooks = $this->getWebhooks();
        
        return view('admin.webhooks.index', compact('webhooks'));
    }
    
    private function getWebhooks()
    {
        // Placeholder for webhooks data
        return [
            [
                'id' => 1,
                'name' => 'Student Registration Webhook',
                'url' => 'https://api.example.com/webhooks/student-registration',
                'events' => ['student.created', 'student.updated'],
                'status' => 'active',
                'last_triggered' => now()->subHours(2),
                'success_rate' => 98.5
            ],
            [
                'id' => 2,
                'name' => 'Attendance Alert Webhook',
                'url' => 'https://api.example.com/webhooks/attendance-alert',
                'events' => ['attendance.alert'],
                'status' => 'active',
                'last_triggered' => now()->subDays(1),
                'success_rate' => 100.0
            ]
        ];
    }
}
