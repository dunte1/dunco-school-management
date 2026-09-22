<?php

namespace Modules\Nursing\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Nursing\Models\Placement;
use Modules\Nursing\Models\LogbookEntry;
use Modules\Nursing\Models\Skill;
use Modules\Nursing\Models\ClinicalHours;

class NursingApiController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $student = $user->academicStudent;

        if (!$student) {
            return response()->json(['error' => 'No student profile found'], 404);
        }

        $stats = [
            'active_placements' => Placement::where('student_id', $student->id)->where('status', 'active')->count(),
            'total_clinical_hours' => ClinicalHours::where('student_id', $student->id)->where('status', 'approved')->sum('hours'),
            'pending_logbooks' => LogbookEntry::where('student_id', $student->id)->whereIn('status', ['draft', 'returned'])->count(),
            'approved_logbooks' => LogbookEntry::where('student_id', $student->id)->where('status', 'approved')->count(),
        ];

        return response()->json(['data' => $stats]);
    }

    public function placements(Request $request)
    {
        $user = $request->user();
        $student = $user->academicStudent;

        $placements = Placement::where('student_id', $student->id)
            ->with(['facility', 'department', 'ward'])
            ->latest()
            ->paginate(20);

        return response()->json($placements);
    }

    public function logbook(Request $request)
    {
        $user = $request->user();
        $student = $user->academicStudent;

        $logbooks = LogbookEntry::where('student_id', $student->id)
            ->with(['placement.facility'])
            ->latest('date')
            ->paginate(20);

        return response()->json($logbooks);
    }

    public function storeLogbook(Request $request)
    {
        $validated = $request->validate([
            'placement_id' => 'required|exists:nursing_placements,id',
            'date' => 'required|date|before_or_equal:today',
            'shift' => 'nullable|string|max:50',
            'hours' => 'required|numeric|min:0.5|max:16',
            'activity' => 'nullable|string',
            'procedure' => 'nullable|string',
            'learning_objective' => 'nullable|string',
            'reflection' => 'nullable|string',
            'challenges' => 'nullable|string',
            'evidence' => 'nullable|string',
        ]);

        $user = $request->user();
        $student = $user->academicStudent;

        $validated['school_id'] = $user->school_id;
        $validated['student_id'] = $student->id;
        $validated['status'] = 'draft';

        $logbook = LogbookEntry::create($validated);

        return response()->json(['data' => $logbook], 201);
    }

    public function skills(Request $request)
    {
        $skills = Skill::published()->with('category')->get();
        return response()->json(['data' => $skills]);
    }

    public function hours(Request $request)
    {
        $user = $request->user();
        $student = $user->academicStudent;

        $hours = ClinicalHours::where('student_id', $student->id)
            ->with('placement.facility')
            ->latest('date')
            ->paginate(20);

        return response()->json($hours);
    }

    public function markAttendance(Request $request)
    {
        $validated = $request->validate([
            'placement_id' => 'required|exists:nursing_placements,id',
            'date' => 'required|date|before_or_equal:today',
            'hours' => 'required|numeric|min:0.5|max:12',
            'shift' => 'nullable|string|max:50',
        ]);

        $user = $request->user();
        $student = $user->academicStudent;

        $validated['school_id'] = $user->school_id;
        $validated['student_id'] = $student->id;
        $validated['status'] = 'pending';

        $record = ClinicalHours::create($validated);

        return response()->json(['data' => $record], 201);
    }
}
