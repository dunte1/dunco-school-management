<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\Placement;
use Modules\Nursing\Models\LogbookEntry;
use Modules\Nursing\Models\Skill;
use Modules\Nursing\Models\StudentSkill;
use Modules\Nursing\Models\ClinicalHours;
use Modules\Nursing\Models\CpdActivity;
use Modules\Nursing\Models\NursingAuditLog;

class NursingDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return $this->adminDashboard($schoolId);
        }

        if ($user->hasAnyRole(['nursing_instructor', 'clinical_instructor'])) {
            return $this->instructorDashboard($user);
        }

        return $this->studentDashboard($user);
    }

    private function adminDashboard(int $schoolId)
    {
        $stats = [
            'total_students' => \Modules\Academic\Models\Student::where('school_id', $schoolId)->count(),
            'active_placements' => Placement::where('school_id', $schoolId)->where('status', 'active')->count(),
            'total_facilities' => \Modules\Nursing\Models\Facility::where('school_id', $schoolId)->where('is_active', true)->count(),
            'pending_logbooks' => LogbookEntry::where('school_id', $schoolId)->whereIn('status', ['submitted', 'under_review'])->count(),
            'total_clinical_hours' => ClinicalHours::where('school_id', $schoolId)->where('status', 'approved')->sum('hours'),
            'pending_cpd' => CpdActivity::where('school_id', $schoolId)->where('status', 'pending')->count(),
            'competent_skills' => StudentSkill::whereHas('student', fn($q) => $q->where('school_id', $schoolId))->where('status', 'competent')->count(),
            'total_skills' => Skill::where('is_active', true)->count(),
        ];

        $recent_logbooks = LogbookEntry::where('school_id', $schoolId)
            ->with(['student', 'placement.facility'])
            ->latest()
            ->take(10)
            ->get();

        $active_placements = Placement::where('school_id', $schoolId)
            ->where('status', 'active')
            ->with(['student', 'facility', 'instructor'])
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Nursing/Dashboard', compact('stats', 'recent_logbooks', 'active_placements'));
    }

    private function instructorDashboard($user)
    {
        $staff = $user->staff;
        $schoolId = $user->school_id;

        $stats = [
            'assigned_students' => Placement::where('school_id', $schoolId)
                ->where('instructor_id', $staff?->id)
                ->where('status', 'active')
                ->count(),
            'pending_reviews' => LogbookEntry::where('school_id', $schoolId)
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

        return Inertia::render('Nursing/Dashboard', compact('stats', 'pending_logbooks', 'my_students'));
    }

    private function studentDashboard($user)
    {
        $student = $user->academicStudent;
        $schoolId = $user->school_id;

        if (!$student) {
            return Inertia::render('Nursing/Dashboard', ['stats' => [], 'message' => 'No student profile found']);
        }

        $stats = [
            'active_placements' => Placement::where('student_id', $student->id)->where('status', 'active')->count(),
            'total_clinical_hours' => ClinicalHours::where('student_id', $student->id)->where('status', 'approved')->sum('hours'),
            'pending_logbooks' => LogbookEntry::where('student_id', $student->id)->whereIn('status', ['draft', 'returned'])->count(),
            'submitted_logbooks' => LogbookEntry::where('student_id', $student->id)->whereIn('status', ['submitted', 'under_review'])->count(),
            'approved_logbooks' => LogbookEntry::where('student_id', $student->id)->where('status', 'approved')->count(),
            'competent_skills' => StudentSkill::where('student_id', $student->id)->where('status', 'competent')->count(),
            'total_skills' => Skill::where('is_active', true)->count(),
            'cpd_hours_this_year' => CpdActivity::where('user_id', $user->id)->where('status', 'approved')->whereYear('activity_date', now()->year)->sum('hours'),
        ];

        $current_placement = Placement::where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['facility', 'department', 'ward', 'instructor'])
            ->first();

        $recent_logbooks = LogbookEntry::where('student_id', $student->id)
            ->with(['placement.facility'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Nursing/Dashboard', compact('stats', 'current_placement', 'recent_logbooks'));
    }
}
