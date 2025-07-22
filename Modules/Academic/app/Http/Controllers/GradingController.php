<?php

namespace Modules\Academic\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\app\Models\GradingScale;
use Modules\Academic\app\Models\Grade;

class GradingController extends Controller
{
    // Grading Scales
    public function index()
    {
        $scales = GradingScale::with('grades')->get();
        return view('academic::grading.index', compact('scales'));
    }

    public function create()
    {
        return view('academic::grading.create');
    }

    public function store(Request $request)
    {
        $scale = GradingScale::create($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));
        return redirect()->route('academic.grading.index')->with('success', 'Grading scale created.');
    }

    public function edit($id)
    {
        $scale = GradingScale::with('grades')->findOrFail($id);
        return view('academic::grading.edit', compact('scale'));
    }

    public function update(Request $request, $id)
    {
        $scale = GradingScale::findOrFail($id);
        $scale->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));
        return redirect()->route('academic.grading.index')->with('success', 'Grading scale updated.');
    }

    public function destroy($id)
    {
        $scale = GradingScale::findOrFail($id);
        $scale->delete();
        return redirect()->route('academic.grading.index')->with('success', 'Grading scale deleted.');
    }

    // Grades (nested under grading scale)
    public function storeGrade(Request $request, $scaleId)
    {
        $scale = GradingScale::findOrFail($scaleId);
        $scale->grades()->create($request->validate([
            'name' => 'required|string|max:10',
            'min_score' => 'required|integer|min:0|max:100',
            'max_score' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string',
        ]));
        return redirect()->route('academic.grading.edit', $scaleId)->with('success', 'Grade added.');
    }

    public function updateGrade(Request $request, $scaleId, $gradeId)
    {
        $grade = Grade::where('grading_scale_id', $scaleId)->findOrFail($gradeId);
        $grade->update($request->validate([
            'name' => 'required|string|max:10',
            'min_score' => 'required|integer|min:0|max:100',
            'max_score' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string',
        ]));
        return redirect()->route('academic.grading.edit', $scaleId)->with('success', 'Grade updated.');
    }

    public function destroyGrade($scaleId, $gradeId)
    {
        $grade = Grade::where('grading_scale_id', $scaleId)->findOrFail($gradeId);
        $grade->delete();
        return redirect()->route('academic.grading.edit', $scaleId)->with('success', 'Grade deleted.');
    }
} 