<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::paginate(15);
        return view('settings::index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'required',
            'type' => 'required|string',
            'description' => 'nullable|string',
        ]);
        Setting::create($validated);
        return redirect()->route('settings.index')->with('success', 'Setting created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings::show', compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings::edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);
        $validated = $request->validate([
            'key' => 'required|string|unique:settings,key,' . $setting->id,
            'value' => 'required',
            'type' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $setting->update($validated);
        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully.');
    }

    /**
     * Show the global settings form.
     */
    public function global()
    {
        $keys = [
            'system_name', 'logo_path', 'favicon_path', 'default_language', 'default_timezone',
            'enable_exams', 'enable_finance', 'enable_attendance', 'enable_library', 'enable_notifications',
            'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'smtp_from_address',
            'sms_gateway_url', 'sms_api_key', 'sms_sender_id', 'api_token',
            'fcm_server_key', 'africastalking_username', 'africastalking_api_key'
        ];
        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key');
        // For dropdowns
        $languages = ['en' => 'English', 'fr' => 'French', 'es' => 'Spanish'];
        $timezones = \DateTimeZone::listIdentifiers();
        return view('settings::global', compact('settings', 'languages', 'timezones'));
    }

    /**
     * Update the global settings.
     */
    public function updateGlobal(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'default_language' => 'required|string',
            'default_timezone' => 'required|string',
            'enable_exams' => 'nullable|boolean',
            'enable_finance' => 'nullable|boolean',
            'enable_attendance' => 'nullable|boolean',
            'enable_library' => 'nullable|boolean',
            'enable_notifications' => 'nullable|boolean',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|string',
            'smtp_user' => 'nullable|string',
            'smtp_pass' => 'nullable|string',
            'smtp_encryption' => 'nullable|string',
            'smtp_from_address' => 'nullable|email',
            'sms_gateway_url' => 'nullable|string',
            'sms_api_key' => 'nullable|string',
            'sms_sender_id' => 'nullable|string',
            'api_token' => 'nullable|string',
            'fcm_server_key' => 'nullable|string',
            'africastalking_username' => 'nullable|string',
            'africastalking_api_key' => 'nullable|string',
            'delete_logo' => 'nullable|boolean',
            'delete_favicon' => 'nullable|boolean',
        ]);

        // Handle logo deletion
        if ($request->boolean('delete_logo')) {
            Setting::where('key', 'logo_path')->delete();
        } elseif ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $logoPath, 'type' => 'string']);
        }
        // Handle favicon deletion
        if ($request->boolean('delete_favicon')) {
            Setting::where('key', 'favicon_path')->delete();
        } elseif ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'favicon_path'], ['value' => $faviconPath, 'type' => 'string']);
        }

        // Save other settings
        $fields = [
            'system_name', 'default_language', 'default_timezone',
            'enable_exams', 'enable_finance', 'enable_attendance', 'enable_library', 'enable_notifications',
            'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'smtp_from_address',
            'sms_gateway_url', 'sms_api_key', 'sms_sender_id', 'api_token',
            'fcm_server_key', 'africastalking_username', 'africastalking_api_key'
        ];
        foreach ($fields as $field) {
            $value = $request->input($field);
            
            // Handle boolean fields
            if (in_array($field, ['enable_exams', 'enable_finance', 'enable_attendance', 'enable_library', 'enable_notifications'])) {
                $value = $request->boolean($field) ? '1' : '0';
            }
            
            // Ensure value is never null - convert to empty string if null
            if ($value === null) {
                $value = '';
            }
            
            Setting::updateOrCreate(['key' => $field], ['value' => $value, 'type' => 'string']);
        }

        return redirect()->route('settings.global')->with('success', 'Global settings updated successfully.');
    }

    /**
     * Show the per-school settings form.
     */
    public function perSchool(Request $request)
    {
        $schools = \App\Models\School::all();
        $schoolId = $request->input('school_id', $schools->first()?->id);
        $keys = [
            'grading_system', 'attendance_type', 'term_start', 'term_end', 'default_currency', 'fee_structure', 'school_notice'
        ];
        $settings = Setting::where('school_id', $schoolId)->whereIn('key', $keys)->pluck('value', 'key');
        $attendanceTypes = ['biometric' => 'Biometric', 'manual' => 'Manual', 'qr' => 'QR'];
        $currencies = ['USD' => 'USD', 'EUR' => 'EUR', 'NGN' => 'NGN', 'KES' => 'KES'];
        return view('settings::per_school', compact('schools', 'schoolId', 'settings', 'attendanceTypes', 'currencies'));
    }

    /**
     * Update per-school settings.
     */
    public function updatePerSchool(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'grading_system' => 'nullable|string',
            'attendance_type' => 'nullable|string',
            'term_start' => 'nullable|date',
            'term_end' => 'nullable|date|after_or_equal:term_start',
            'default_currency' => 'nullable|string',
            'fee_structure' => 'nullable|string',
            'school_notice' => 'nullable|string',
        ]);
        $schoolId = $request->input('school_id');
        $fields = ['grading_system', 'attendance_type', 'term_start', 'term_end', 'default_currency', 'fee_structure', 'school_notice'];
        foreach ($fields as $field) {
            $value = $request->input($field, '');
            if ($value === null) $value = '';
            Setting::updateOrCreate(
                ['school_id' => $schoolId, 'key' => $field],
                ['value' => $value, 'type' => 'string']
            );
        }
        return redirect()->route('settings.per_school', ['school_id' => $schoolId])->with('success', 'Per-school settings updated successfully.');
    }
}
