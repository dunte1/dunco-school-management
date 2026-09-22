<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\Placement;
use Modules\Nursing\Models\LogbookEntry;
use Modules\Nursing\Models\StudentSkill;
use Modules\Nursing\Models\ClinicalHours;
use Modules\Academic\Models\Student;

class InstructorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $staff = $user->staff;
        $schoolId = $user->school_id;

        $stats = [
            'assigned_students' => Placement::where('school_id', $schoolId)
                ->where('instructor_id', $staff?->id)
                ->where('status', 'active')
                ->count(),
            'pending_logbooks' => LogbookEntry::where('school_id', $schoolId)
                ->whereIn('status', ['submitted', 'under_review'])
                ->count(),
            'total_assessments' => \Modules\Nursing\Models\SkillAssessment::where('assessor_id', $user->id)->count(),
            'students_needing_remediation' => StudentSkill::where('status', 'remediation_required')
                ->whereHas('student', fn($q) => $q->where('school_id', $schoolId))
                ->count(),
        ];

        $pending_logbooks = LogbookEntry::where('school_id', $schoolId)
            ->whereIn('status', ['submitted', 'under_review'])
            ->with(['student', 'placement.facility'])
            ->latest()
            ->take(10)
            ->get();

        $my_students = Placement::where('school_id', $schoolId)
            ->where('instructor_id', $staff?->id)
            ->where('status', 'active')
            ->with(['student', 'facility'])
            ->get();

        return Inertia::render('Nursing/Instructor/Dashboard', compact('stats', 'pending_logbooks', 'my_students'));
    }

    public function students()
    {
        $user = Auth::user();
        $staff = $user->staff;
        $schoolId = $user->school_id;

        $placements = Placement::where('school_id', $schoolId)
            ->where('instructor_id', $staff?->id)
            ->where('status', 'active')
            ->with(['student', 'facility', 'department'])
            ->get();

        return Inertia::render('Nursing/Instructor/Students', compact('placements'));
    }

    public function studentDetail(Student $student)
    {
        $user = Auth::user();
        $staff = $user->staff;
        $schoolId = $user->school_id;

        $placement = Placement::where('school_id', $schoolId)
            ->where('instructor_id', $staff?->id)
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['facility', 'department', 'ward'])
            ->first();

        if (!$placement) {
            return back()->with('error', 'Student not found in your assigned list.');
        }

        $logbooks = LogbookEntry::where('student_id', $student->id)
            ->latest('date')
            ->take(20)
            ->get();

        $skills = StudentSkill::where('student_id', $student->id)
            ->with('skill.category')
            ->get();

        $hours = ClinicalHours::where('student_id', $student->id)
            ->where('placement_id', $placement->id)
            ->get();

        $completedHours = $hours->where('status', 'approved')->sum('hours');

        return Inertia::render('Nursing/Instructor/StudentDetail', compact(
            'student', 'placement', 'logbooks', 'skills', 'hours', 'completedHours'
        ));
    }
}
