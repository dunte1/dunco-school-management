<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SchoolSettingController extends Controller
{
    public function index()
    {
        $settings = SchoolSetting::paginate(20);
        return view('core::settings.index', compact('settings'));
    }

    public function edit($id)
    {
        $setting = SchoolSetting::findOrFail($id);
        return view('core::settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = SchoolSetting::findOrFail($id);
        $oldValues = $setting->toArray();
        
        $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
        ]);
        
        $setting->update($request->only(['key', 'value']));

        // Log the action
        AuditLog::log(
            'setting.updated',
            "Setting '{$setting->key}' was updated",
            $oldValues,
            $setting->toArray()
        );

        return redirect()->route('core.settings.index')->with('success', 'Setting updated successfully.');
    }
} 