<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\Placement;
use Modules\Nursing\Models\Facility;
use Modules\Nursing\Models\FacilityDepartment;
use Modules\Nursing\Models\Ward;
use Modules\Nursing\Models\NursingAuditLog;
use Modules\Academic\Models\Student;
use Modules\HR\Models\Staff;

class PlacementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        $query = Placement::where('school_id', $schoolId)
            ->with(['student', 'facility', 'department', 'ward', 'instructor']);

        if ($user->hasAnyRole(['student'])) {
            $student = $user->academicStudent;
            $query->where('student_id', $student?->id ?? 0);
        } elseif ($user->hasAnyRole(['nursing_instructor', 'clinical_instructor'])) {
            $staff = $user->staff;
            $query->where('instructor_id', $staff?->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $placements = $query->latest()->paginate(15);
        $facilities = Facility::where('school_id', $schoolId)->where('is_active', true)->get();

        return Inertia::render('Nursing/Placements/Index', compact('placements', 'facilities'));
    }

    public function create()
    {
        $schoolId = Auth::user()->school_id;
        $facilities = Facility::where('school_id', $schoolId)->where('is_active', true)->get();
        $students = Student::where('school_id', $schoolId)->where('is_active', true)->get();
        $instructors = Staff::where('school_id', $schoolId)->where('status', 'active')->get();

        return Inertia::render('Nursing/Placements/Create', compact('facilities', 'students', 'instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:academic_students,id',
            'facility_id' => 'required|exists:nursing_facilities,id',
            'department_id' => 'required|exists:nursing_facility_departments,id',
            'ward_id' => 'nullable|exists:nursing_wards,id',
            'instructor_id' => 'nullable|exists:staff,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'required_hours' => 'required|numeric|min:1|max:999',
            'notes' => 'nullable|string',
        ]);

        $validated['school_id'] = Auth::user()->school_id;
        $validated['status'] = 'planned';

        $placement = Placement::create($validated);

        NursingAuditLog::log('placement_created', $placement, null, $validated);

        return redirect()->route('nursing.placements.show', $placement)
            ->with('success', 'Placement created successfully.');
    }

    public function show(Placement $placement)
    {
        $this->authorize('view', $placement);

        $placement->load([
            'student', 'facility', 'department', 'ward', 'instructor',
            'logbooks' => fn($q) => $q->latest()->take(10),
            'clinicalHours' => fn($q) => $q->latest()->take(10),
        ]);

        $completedHours = $placement->clinicalHours()->where('status', 'approved')->sum('hours');
        $remainingHours = max(0, $placement->required_hours - $completedHours);
        $progressPercentage = $placement->required_hours > 0
            ? min(100, round(($completedHours / $placement->required_hours) * 100, 1))
            : 0;

        return Inertia::render('Nursing/Placements/Show', compact('placement', 'completedHours', 'remainingHours', 'progressPercentage'));
    }

    public function edit(Placement $placement)
    {
        $this->authorize('update', $placement);

        $schoolId = Auth::user()->school_id;
        $facilities = Facility::where('school_id', $schoolId)->where('is_active', true)->get();
        $students = Student::where('school_id', $schoolId)->where('is_active', true)->get();
        $instructors = Staff::where('school_id', $schoolId)->where('status', 'active')->get();

        return Inertia::render('Nursing/Placements/Edit', compact('placement', 'facilities', 'students', 'instructors'));
    }

    public function update(Request $request, Placement $placement)
    {
        $this->authorize('update', $placement);

        $validated = $request->validate([
            'facility_id' => 'required|exists:nursing_facilities,id',
            'department_id' => 'required|exists:nursing_facility_departments,id',
            'ward_id' => 'nullable|exists:nursing_wards,id',
            'instructor_id' => 'nullable|exists:staff,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'required_hours' => 'required|numeric|min:1|max:999',
            'status' => 'required|in:planned,active,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldValues = $placement->only(array_keys($validated));
        $placement->update($validated);

        NursingAuditLog::log('placement_updated', $placement, $oldValues, $validated);

        return redirect()->route('nursing.placements.show', $placement)
            ->with('success', 'Placement updated successfully.');
    }

    public function destroy(Placement $placement)
    {
        $this->authorize('delete', $placement);

        $placement->delete();

        NursingAuditLog::log('placement_deleted', $placement);

        return redirect()->route('nursing.placements.index')
            ->with('success', 'Placement deleted successfully.');
    }
}
