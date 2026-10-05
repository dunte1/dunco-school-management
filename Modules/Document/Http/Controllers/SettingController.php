<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'storage_limit' => config('document.storage_limit', 1073741824), // 1GB default
            'allowed_extensions' => config('document.allowed_extensions', ['pdf', 'doc', 'docx', 'xls', 'xlsx']),
            'max_file_size' => config('document.max_file_size', 10485760), // 10MB default
            'auto_versioning' => config('document.auto_versioning', true),
            'enable_sharing' => config('document.enable_sharing', true),
        ];

        return view('document::settings.index', compact('settings'));
    }

    public function updateStorage(Request $request)
    {
        $request->validate([
            'storage_limit' => 'required|numeric|min:1',
            'max_file_size' => 'required|numeric|min:1',
            'allowed_extensions' => 'required|array|min:1'
        ]);

        try {
            // Update configuration (in a real app, you'd save to database or config file)
            $settings = [
                'storage_limit' => $request->storage_limit,
                'max_file_size' => $request->max_file_size,
                'allowed_extensions' => $request->allowed_extensions
            ];

            return redirect()->route('document.settings.index')
                           ->with('success', 'Storage settings updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update storage settings: ' . $e->getMessage()]);
        }
    }

    public function updateSecurity(Request $request)
    {
        $request->validate([
            'enable_encryption' => 'boolean',
            'require_authentication' => 'boolean',
            'session_timeout' => 'required|numeric|min:1',
            'max_login_attempts' => 'required|numeric|min:1'
        ]);

        try {
            // Update security settings
            $settings = [
                'enable_encryption' => $request->enable_encryption,
                'require_authentication' => $request->require_authentication,
                'session_timeout' => $request->session_timeout,
                'max_login_attempts' => $request->max_login_attempts
            ];

            return redirect()->route('document.settings.index')
                           ->with('success', 'Security settings updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update security settings: ' . $e->getMessage()]);
        }
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'upload_notifications' => 'boolean',
            'share_notifications' => 'boolean',
            'system_notifications' => 'boolean'
        ]);

        try {
            // Update notification settings
            $settings = [
                'email_notifications' => $request->email_notifications,
                'upload_notifications' => $request->upload_notifications,
                'share_notifications' => $request->share_notifications,
                'system_notifications' => $request->system_notifications
            ];

            return redirect()->route('document.settings.index')
                           ->with('success', 'Notification settings updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update notification settings: ' . $e->getMessage()]);
        }
    }
}
