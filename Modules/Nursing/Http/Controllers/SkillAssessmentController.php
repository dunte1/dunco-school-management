<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\SkillAssessment;
use Modules\Nursing\Models\Skill;
use Modules\Nursing\Models\StudentSkill;
use Modules\Nursing\Models\SkillRubric;
use Modules\Nursing\Models\NursingAuditLog;

class SkillAssessmentController extends Controller
{
    public function index(Request $request)
    {
        $query = SkillAssessment::with(['student', 'skill', 'assessor']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('skill_id')) {
            $query->where('skill_id', $request->skill_id);
        }
        if ($request->filled('result')) {
            $query->where('result', $request->result);
        }

        $assessments = $query->latest('assessed_at')->paginate(15);

        return Inertia::render('Nursing/Skills/Assessments', compact('assessments'));
    }

    public function create(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $skills = Skill::published()->with('category')->orderBy('name')->get();
        $rubrics = SkillRubric::where('is_active', true)->get();

        $students = \Modules\Academic\Models\Student::where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        return Inertia::render('Nursing/Skills/AssessCreate', compact('skills', 'rubrics', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:academic_students,id',
            'skill_id' => 'required|exists:nursing_skills,id',
            'rubric_id' => 'nullable|exists:nursing_skill_rubrics,id',
            'criteria_scores' => 'nullable|array',
            'score' => 'required|numeric|min:0',
            'maximum_score' => 'required|numeric|min:1',
            'feedback' => 'nullable|string',
            'recommendation' => 'nullable|string',
        ]);

        $validated['assessor_id'] = Auth::id();
        $validated['percentage'] = round(($validated['score'] / $validated['maximum_score']) * 100, 2);
        $validated['result'] = $validated['percentage'] >= 60 ? 'pass' : 'fail';
        $validated['assessed_at'] = now();

        $assessment = SkillAssessment::create($validated);

        $studentSkill = StudentSkill::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'skill_id' => $validated['skill_id'],
            ],
            [
                'status' => $validated['result'] === 'pass' ? 'competent' : 'remediation_required',
                'awarded_by' => Auth::id(),
                'awarded_at' => now(),
            ]
        );

        NursingAuditLog::log('skill_assessed', $assessment, null, $validated);

        return redirect()->route('nursing.skill-assessments.show', $assessment)
            ->with('success', 'Assessment completed successfully.');
    }

    public function show(SkillAssessment $assessment)
    {
        $assessment->load(['student', 'skill', 'assessor', 'rubric']);

        return Inertia::render('Nursing/Skills/AssessShow', compact('assessment'));
    }
}
