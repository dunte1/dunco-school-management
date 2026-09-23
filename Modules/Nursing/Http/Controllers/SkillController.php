<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\Skill;
use Modules\Nursing\Models\SkillCategory;
use Modules\Nursing\Models\StudentSkill;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $query = Skill::published()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $skills = $query->orderBy('name')->paginate(20);
        $categories = SkillCategory::where('is_active', true)->orderBy('sort_order')->get();

        return Inertia::render('Nursing/Skills/Index', compact('skills', 'categories'));
    }

    public function show(Skill $skill)
    {
        $skill->load('category', 'author', 'reviewer');

        $user = Auth::user();
        $student = $user->academicStudent;

        $studentSkill = null;
        if ($student) {
            $studentSkill = StudentSkill::where('student_id', $student->id)
                ->where('skill_id', $skill->id)
                ->with('assessments.assessor')
                ->first();
        }

        return Inertia::render('Nursing/Skills/Show', compact('skill', 'studentSkill'));
    }

    public function myProgress()
    {
        $user = Auth::user();
        $student = $user->academicStudent;

        if (!$student) {
            return Inertia::render('Nursing/Skills/MyProgress', [
                'studentSkills' => collect(),
                'categories' => collect(),
                'stats' => ['total' => 0, 'competent' => 0, 'in_progress' => 0, 'remediation' => 0],
            ]);
        }

        $studentSkills = StudentSkill::where('student_id', $student->id)
            ->with(['skill.category', 'placement.facility'])
            ->get();

        $categories = SkillCategory::where('is_active', true)->with(['skills' => function ($q) {
            $q->published();
        }])->orderBy('sort_order')->get();

        $stats = [
            'total' => Skill::published()->count(),
            'competent' => $studentSkills->where('status', 'competent')->count(),
            'in_progress' => $studentSkills->whereIn('status', ['learning', 'observed', 'assisted', 'performed_supervised'])->count(),
            'remediation' => $studentSkills->where('status', 'remediation_required')->count(),
        ];

        return Inertia::render('Nursing/Skills/MyProgress', compact('studentSkills', 'categories', 'stats'));
    }

    public function updateStatus(Request $request, Skill $skill)
    {
        $request->validate([
            'student_id' => 'required|exists:academic_students,id',
            'status' => 'required|in:not_started,learning,observed,assisted,performed_supervised,competent,remediation_required',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        if ($user->hasAnyRole(['student'])) {
            if ($request->status === 'competent') {
                return back()->with('error', 'Students cannot mark themselves as competent. Only authorized instructors can award competency.');
            }
            $student = $user->academicStudent;
            if (!$student || $student->id !== $request->student_id) {
                return back()->with('error', 'Unauthorized action.');
            }
        }

        $studentSkill = StudentSkill::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'skill_id' => $skill->id,
            ],
            [
                'status' => $request->status,
                'awarded_by' => in_array($request->status, ['competent', 'remediation_required']) ? $user->id : null,
                'awarded_at' => in_array($request->status, ['competent', 'remediation_required']) ? now() : null,
                'notes' => $request->notes,
            ]
        );

        return back()->with('success', 'Skill status updated successfully.');
    }
}
