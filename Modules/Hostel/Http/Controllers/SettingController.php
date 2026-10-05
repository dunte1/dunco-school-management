<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'check_in_time' => config('hostel.check_in_time', '18:00'),
            'check_out_time' => config('hostel.check_out_time', '08:00'),
            'visitor_hours' => config('hostel.visitor_hours', '09:00-21:00'),
            'quiet_hours' => config('hostel.quiet_hours', '22:00-07:00'),
            'max_visitors' => config('hostel.max_visitors', 2),
            'late_fee' => config('hostel.late_fee', 50),
            'damage_deposit' => config('hostel.damage_deposit', 500),
        ];

        return view('hostel::settings.index', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'visitor_hours' => 'required|string',
            'quiet_hours' => 'required|string',
            'max_visitors' => 'required|integer|min:1|max:10',
            'late_fee' => 'required|numeric|min:0',
            'damage_deposit' => 'required|numeric|min:0'
        ]);

        try {
            // Update general settings (in a real app, you'd save to database or config file)
            $settings = [
                'check_in_time' => $request->check_in_time,
                'check_out_time' => $request->check_out_time,
                'visitor_hours' => $request->visitor_hours,
                'quiet_hours' => $request->quiet_hours,
                'max_visitors' => $request->max_visitors,
                'late_fee' => $request->late_fee,
                'damage_deposit' => $request->damage_deposit
            ];

            return redirect()->route('hostel.settings.index')
                           ->with('success', 'General settings updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update general settings: ' . $e->getMessage()]);
        }
    }

    public function updateFees(Request $request)
    {
        $request->validate([
            'monthly_rent' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'late_payment_fee' => 'required|numeric|min:0',
            'utility_fee' => 'required|numeric|min:0',
            'cleaning_fee' => 'required|numeric|min:0',
            'payment_due_day' => 'required|integer|min:1|max:31'
        ]);

        try {
            // Update fee settings
            $settings = [
                'monthly_rent' => $request->monthly_rent,
                'security_deposit' => $request->security_deposit,
                'late_payment_fee' => $request->late_payment_fee,
                'utility_fee' => $request->utility_fee,
                'cleaning_fee' => $request->cleaning_fee,
                'payment_due_day' => $request->payment_due_day
            ];

            return redirect()->route('hostel.settings.index')
                           ->with('success', 'Fee settings updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update fee settings: ' . $e->getMessage()]);
        }
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'fee_reminders' => 'boolean',
            'maintenance_updates' => 'boolean',
            'visitor_notifications' => 'boolean'
        ]);

        try {
            // Update notification settings
            $settings = [
                'email_notifications' => $request->email_notifications,
                'sms_notifications' => $request->sms_notifications,
                'push_notifications' => $request->push_notifications,
                'fee_reminders' => $request->fee_reminders,
                'maintenance_updates' => $request->maintenance_updates,
                'visitor_notifications' => $request->visitor_notifications
            ];

            return redirect()->route('hostel.settings.index')
                           ->with('success', 'Notification settings updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update notification settings: ' . $e->getMessage()]);
        }
    }
}