<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegrationsController extends Controller
{
    public function index()
    {
        $integrations = $this->getIntegrations();
        
        return view('admin.integrations.index', compact('integrations'));
    }
    
    private function getIntegrations()
    {
        // Placeholder for integrations data
        return [
            [
                'id' => 1,
                'name' => 'Google Classroom',
                'description' => 'Sync with Google Classroom for assignments and grades',
                'status' => 'connected',
                'last_sync' => now()->subHours(1),
                'sync_frequency' => 'Every 30 minutes'
            ],
            [
                'id' => 2,
                'name' => 'Microsoft Teams',
                'description' => 'Integration with Microsoft Teams for communication',
                'status' => 'connected',
                'last_sync' => now()->subHours(2),
                'sync_frequency' => 'Every hour'
            ],
            [
                'id' => 3,
                'name' => 'SMS Gateway',
                'description' => 'SMS notifications for parents and students',
                'status' => 'disconnected',
                'last_sync' => null,
                'sync_frequency' => 'On demand'
            ]
        ];
    }
}
