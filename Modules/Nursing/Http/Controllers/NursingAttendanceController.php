<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\ClinicalHours;
use Modules\Nursing\Models\Placement;

class NursingAttendanceController extends Controller
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

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }
        if ($request->filled('placement_id')) {
            $query->where('placement_id', $request->placement_id);
        }

        $attendance = $query->latest('date')->paginate(15);

        $placements = Placement::where('school_id', $schoolId)->whereIn('status', ['active'])->get();

        return Inertia::render('Nursing/Attendance/Index', compact('attendance', 'placements'));
    }

    public function mark(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:academic_students,id',
            'placement_id' => 'required|exists:nursing_placements,id',
            'date' => 'required|date|before_or_equal:today',
            'hours' => 'required|numeric|min:0.5|max:12',
            'shift' => 'nullable|string|max:50',
        ]);

        $validated['school_id'] = Auth::user()->school_id;
        $validated['status'] = 'pending';

        ClinicalHours::create($validated);

        return back()->with('success', 'Clinical attendance marked successfully.');
    }
}
