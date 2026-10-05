<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\GradingPreset;
use Modules\Examination\Models\GradingPresetAssignment;

class GradingPresetController extends Controller
{
    public function index()
    {
        return view('examination::grading.presets.index', [
            'presets' => GradingPreset::orderBy('name')->paginate(20)
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'school_id' => ['nullable','integer'],
            'bands' => ['required','array','min:1'],
            'bands.*.min' => ['required','integer','between:0,100'],
            'bands.*.max' => ['required','integer','between:0,100'],
            'bands.*.grade' => ['required','string','max:5'],
            'is_active' => ['sometimes','boolean'],
        ]);
        $preset = GradingPreset::create($data);
        return redirect()->back()->with('success', 'Grading preset created');
    }

    public function assign(Request $request, GradingPreset $preset)
    {
        $data = $request->validate([
            'class_id' => ['nullable','integer'],
            'exam_id' => ['nullable','integer'],
        ]);
        $data['grading_preset_id'] = $preset->id;
        GradingPresetAssignment::create($data);
        return back()->with('success', 'Preset assigned');
    }
}


