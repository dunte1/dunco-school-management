<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\LogbookEntry;
use Modules\Nursing\Models\Placement;
use Modules\Nursing\Models\ClinicalHours;
use Modules\Nursing\Models\NursingAuditLog;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        $query = LogbookEntry::where('school_id', $schoolId)->with(['student', 'placement.facility']);

        if ($user->hasAnyRole(['student'])) {
            $student = $user->academicStudent;
            $query->where('student_id', $student?->id ?? 0);
        } elseif ($user->hasAnyRole(['nursing_instructor', 'clinical_instructor'])) {
            $staff = $user->staff;
            $studentIds = Placement::where('instructor_id', $staff?->id)->pluck('student_id');
            $query->whereIn('student_id', $studentIds);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $logbooks = $query->latest('date')->paginate(15);

        return Inertia::render('Nursing/Logbook/Index', compact('logbooks'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $student = $user->academicStudent;
        $placementId = $request->get('placement_id');

        $placements = Placement::where('student_id', $student?->id)
            ->whereIn('status', ['active', 'planned'])
            ->with(['facility', 'department', 'ward'])
            ->get();

        $placement = $placementId ? $placements->firstWhere('id', $placementId) : $placements->first();

        return Inertia::render('Nursing/Logbook/Create', compact('placements', 'placement'));
    }

    public function store(Request $request)
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

        $user = Auth::user();
        $student = $user->academicStudent;

        $validated['school_id'] = $user->school_id;
        $validated['student_id'] = $student->id;
        $validated['status'] = 'draft';

        $logbook = LogbookEntry::create($validated);

        NursingAuditLog::log('logbook_created', $logbook, null, $validated);

        return redirect()->route('nursing.logbook.show', $logbook)
            ->with('success', 'Logbook entry created successfully.');
    }

    public function show(LogbookEntry $logbook)
    {
        $this->authorize('view', $logbook);

        $logbook->load(['student', 'placement.facility', 'placement.department', 'placement.ward', 'reviewer', 'approver', 'corrections']);

        return Inertia::render('Nursing/Logbook/Show', compact('logbook'));
    }

    public function edit(LogbookEntry $logbook)
    {
        if (!$logbook->isEditable()) {
            return back()->with('error', 'This logbook entry cannot be edited.');
        }

        $this->authorize('update', $logbook);

        $placements = Placement::where('student_id', $logbook->student_id)
            ->whereIn('status', ['active', 'planned'])
            ->with(['facility', 'department', 'ward'])
            ->get();

        return Inertia::render('Nursing/Logbook/Edit', compact('logbook', 'placements'));
    }

    public function update(Request $request, LogbookEntry $logbook)
    {
        if (!$logbook->isEditable()) {
            return back()->with('error', 'This logbook entry cannot be edited.');
        }

        $this->authorize('update', $logbook);

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

        $oldValues = $logbook->only(array_keys($validated));
        $logbook->update($validated);

        NursingAuditLog::log('logbook_updated', $logbook, $oldValues, $validated);

        return redirect()->route('nursing.logbook.show', $logbook)
            ->with('success', 'Logbook entry updated successfully.');
    }

    public function destroy(LogbookEntry $logbook)
    {
        $this->authorize('delete', $logbook);

        if ($logbook->status !== 'draft') {
            return back()->with('error', 'Only draft entries can be deleted.');
        }

        $logbook->delete();

        NursingAuditLog::log('logbook_deleted', $logbook);

        return redirect()->route('nursing.logbook.index')
            ->with('success', 'Logbook entry deleted successfully.');
    }

    public function submit(LogbookEntry $logbook)
    {
        if (!$logbook->canBeSubmitted()) {
            return back()->with('error', 'This entry cannot be submitted.');
        }

        $this->authorize('update', $logbook);

        $logbook->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        NursingAuditLog::log('logbook_submitted', $logbook, ['status' => 'draft'], ['status' => 'submitted']);

        return redirect()->route('nursing.logbook.show', $logbook)
            ->with('success', 'Logbook entry submitted for review.');
    }

    public function approve(LogbookEntry $logbook)
    {
        $this->authorize('review', $logbook);

        if (!$logbook->canBeReviewed()) {
            return back()->with('error', 'This entry cannot be reviewed.');
        }

        $logbook->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        ClinicalHours::create([
            'school_id' => $logbook->school_id,
            'student_id' => $logbook->student_id,
            'placement_id' => $logbook->placement_id,
            'date' => $logbook->date,
            'hours' => $logbook->hours,
            'shift' => $logbook->shift,
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'logbook_id' => $logbook->id,
        ]);

        NursingAuditLog::log('logbook_approved', $logbook, ['status' => 'submitted'], ['status' => 'approved']);

        return redirect()->route('nursing.logbook.show', $logbook)
            ->with('success', 'Logbook entry approved. Clinical hours have been recorded.');
    }

    public function returnEntry(LogbookEntry $logbook)
    {
        $this->authorize('review', $logbook);

        request()->validate([
            'review_comments' => 'required|string|min:10',
        ]);

        $logbook->update([
            'status' => 'returned',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
            'review_comments' => request('review_comments'),
        ]);

        NursingAuditLog::log('logbook_returned', $logbook, ['status' => 'submitted'], ['status' => 'returned']);

        return redirect()->route('nursing.logbook.show', $logbook)
            ->with('success', 'Logbook entry returned for revision.');
    }

    public function reject(LogbookEntry $logbook)
    {
        $this->authorize('review', $logbook);

        request()->validate([
            'review_comments' => 'required|string|min:10',
        ]);

        $logbook->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
            'review_comments' => request('review_comments'),
        ]);

        NursingAuditLog::log('logbook_rejected', $logbook, ['status' => 'submitted'], ['status' => 'rejected']);

        return redirect()->route('nursing.logbook.show', $logbook)
            ->with('success', 'Logbook entry rejected.');
    }

    public function pendingReviews()
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        $query = LogbookEntry::where('school_id', $schoolId)
            ->whereIn('status', ['submitted', 'under_review'])
            ->with(['student', 'placement.facility']);

        if ($user->hasAnyRole(['nursing_instructor', 'clinical_instructor'])) {
            $staff = $user->staff;
            $studentIds = Placement::where('instructor_id', $staff?->id)->pluck('student_id');
            $query->whereIn('student_id', $studentIds);
        }

        $logbooks = $query->latest('submitted_at')->paginate(15);

        return Inertia::render('Nursing/Logbook/PendingReviews', compact('logbooks'));
    }
}
