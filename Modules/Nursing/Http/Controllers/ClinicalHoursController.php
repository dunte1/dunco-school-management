<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\ClinicalHours;
use Modules\Nursing\Models\Placement;
use Modules\Nursing\Models\NursingAuditLog;

class ClinicalHoursController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        $query = ClinicalHours::where('school_id', $schoolId)->with(['student', 'placement.facility']);

        if ($user->hasAnyRole(['student'])) {
            $student = $user->academicStudent;
            $query->where('student_id', $student?->id ?? 0);
        }

        if ($request->filled('placement_id')) {
            $query->where('placement_id', $request->placement_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hours = $query->latest('date')->paginate(15);

        $studentId = $user->hasAnyRole(['student']) ? $user->academicStudent?->id : null;
        $placements = $studentId
            ? Placement::where('student_id', $studentId)->whereIn('status', ['active', 'planned'])->get()
            : Placement::where('school_id', $schoolId)->whereIn('status', ['active', 'planned'])->get();

        return Inertia::render('Nursing/Hours/Index', compact('hours', 'placements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'placement_id' => 'required|exists:nursing_placements,id',
            'student_id' => 'required|exists:academic_students,id',
            'date' => 'required|date|before_or_equal:today',
            'hours' => 'required|numeric|min:0.5|max:12',
            'shift' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $validated['school_id'] = Auth::user()->school_id;
        $validated['status'] = 'pending';

        $hoursRecord = ClinicalHours::create($validated);

        NursingAuditLog::log('clinical_hours_added', $hoursRecord, null, $validated);

        return back()->with('success', 'Clinical hours recorded successfully.');
    }

    public function approve(Request $request, ClinicalHours $hours)
    {
        $hours->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        NursingAuditLog::log('clinical_hours_approved', $hours, ['status' => 'pending'], ['status' => 'approved']);

        return back()->with('success', 'Clinical hours approved.');
    }
}
