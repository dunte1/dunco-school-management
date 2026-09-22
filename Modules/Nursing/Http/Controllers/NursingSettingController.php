<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\NursingSetting;

class NursingSettingController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        $settings = NursingSetting::where('school_id', $schoolId)
            ->get()
            ->pluck('value', 'key')
            ->toArray();

        $defaults = [
            'clinical_hours_per_placement' => config('nursing.clinical_hours.required_per_placement', 200),
            'clinical_hours_per_semester' => config('nursing.clinical_hours.required_per_semester', 400),
            'max_hours_per_day' => config('nursing.clinical_hours.max_hours_per_day', 12),
            'attendance_passing' => config('nursing.attendance.passing_percentage', 80),
            'cpd_target_hours' => config('nursing.cpd.target_hours_per_year', 40),
            'logbook_backdating_days' => config('nursing.logbook.allow_backdating_days', 7),
            'allowed_file_types' => config('nursing.files.allowed_types', ['pdf', 'jpg', 'jpeg', 'png']),
            'max_upload_size' => config('nursing.files.max_upload_size', 10240),
        ];

        $settings = array_merge($defaults, $settings);

        return Inertia::render('Nursing/Settings/Index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'clinical_hours_per_placement' => 'required|numeric|min:1',
            'clinical_hours_per_semester' => 'required|numeric|min:1',
            'max_hours_per_day' => 'required|numeric|min:1|max:24',
            'attendance_passing' => 'required|numeric|min:0|max:100',
            'cpd_target_hours' => 'required|numeric|min:1',
            'logbook_backdating_days' => 'required|numeric|min:0',
            'max_upload_size' => 'required|numeric|min:1',
        ]);

        $schoolId = Auth::user()->school_id;

        foreach ($validated as $key => $value) {
            NursingSetting::setValue($key, $value, $schoolId);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
