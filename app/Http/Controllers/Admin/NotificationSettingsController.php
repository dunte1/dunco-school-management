<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{
    public function index()
    {
        // For now, return a simple view or redirect to a placeholder
        // This can be expanded later with actual notification settings functionality
        return view('admin.notifications.settings', [
            'title' => 'Notification Settings',
            'settings' => []
        ]);
    }
}
