<?php

namespace App\Http\Controllers\Modules\Timetable\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Timetable\Models\Timetable;
use Illuminate\Http\Request;

class TimetableManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timetables = Timetable::orderByDesc('is_active')->orderBy('name')->paginate(20);
        return view('timetable::timetables.index', compact('timetables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('timetable::timetables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'academic_year' => 'nullable|string',
            'term' => 'nullable|string',
            'school_level' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        Timetable::create($data);
        return redirect()->route('timetables.index')->with('success', 'Timetable created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $timetable = Timetable::findOrFail($id);
        return view('timetable::timetables.edit', compact('timetable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $timetable = Timetable::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'academic_year' => 'nullable|string',
            'term' => 'nullable|string',
            'school_level' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $timetable->update($data);
        return redirect()->route('timetables.index')->with('success', 'Timetable updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $timetable = Timetable::findOrFail($id);
        $timetable->delete();
        return redirect()->route('timetables.index')->with('success', 'Timetable deleted.');
    }

    /**
     * Submit timetable for approval
     */
    public function submitForApproval($id)
    {
        $timetable = Timetable::findOrFail($id);
        if ($timetable->status !== Timetable::STATUS_DRAFT) {
            return back()->with('error', 'Only draft timetables can be submitted.');
        }
        $timetable->update([
            'status' => Timetable::STATUS_PENDING,
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);
        \App\Models\AuditLog::log('timetable.submit', 'Timetable submitted for approval', $timetable->id, $timetable->toArray());
        // TODO: Notify approvers
        return back()->with('success', 'Timetable submitted for approval.');
    }

    /**
     * Approve timetable
     */
    public function approve($id)
    {
        $timetable = Timetable::findOrFail($id);
        if ($timetable->status !== Timetable::STATUS_PENDING) {
            return back()->with('error', 'Only pending timetables can be approved.');
        }
        $timetable->update([
            'status' => Timetable::STATUS_PUBLISHED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'published_by' => auth()->id(),
            'published_at' => now(),
        ]);
        \App\Models\AuditLog::log('timetable.approve', 'Timetable approved and published', $timetable->id, $timetable->toArray());
        // Notify parents
        $this->notifyParentsOfTimetable($timetable, 'approved');
        return back()->with('success', 'Timetable approved and published.');
    }

    /**
     * Reject timetable
     */
    public function reject($id)
    {
        $timetable = Timetable::findOrFail($id);
        if ($timetable->status !== Timetable::STATUS_PENDING) {
            return back()->with('error', 'Only pending timetables can be rejected.');
        }
        $timetable->update([
            'status' => Timetable::STATUS_DRAFT,
            'approved_by' => null,
            'approved_at' => null,
        ]);
        \App\Models\AuditLog::log('timetable.reject', 'Timetable rejected', $timetable->id, $timetable->toArray());
        // TODO: Notify creator
        return back()->with('success', 'Timetable rejected and reverted to draft.');
    }

    /**
     * Archive timetable
     */
    public function archive($id)
    {
        $timetable = Timetable::findOrFail($id);
        if ($timetable->status !== Timetable::STATUS_PUBLISHED) {
            return back()->with('error', 'Only published timetables can be archived.');
        }
        $timetable->update([
            'status' => Timetable::STATUS_ARCHIVED,
            'archived_by' => auth()->id(),
            'archived_at' => now(),
        ]);
        \App\Models\AuditLog::log('timetable.archive', 'Timetable archived', $timetable->id, $timetable->toArray());
        return back()->with('success', 'Timetable archived.');
    }

    /**
     * Publish timetable (if not using approval)
     */
    public function publish($id)
    {
        $timetable = Timetable::findOrFail($id);
        if (!in_array($timetable->status, [Timetable::STATUS_DRAFT, Timetable::STATUS_PENDING])) {
            return back()->with('error', 'Only draft or pending timetables can be published.');
        }
        $timetable->update([
            'status' => Timetable::STATUS_PUBLISHED,
            'published_by' => auth()->id(),
            'published_at' => now(),
        ]);
        \App\Models\AuditLog::log('timetable.publish', 'Timetable published', $timetable->id, $timetable->toArray());
        // Notify parents
        $this->notifyParentsOfTimetable($timetable, 'published');
        return back()->with('success', 'Timetable published.');
    }

    /**
     * Notify all parents of students in classes affected by the timetable
     */
    protected function notifyParentsOfTimetable($timetable, $action = 'published')
    {
        $classSchedules = \Modules\Timetable\Models\ClassSchedule::where('timetable_id', $timetable->id)->with(['class', 'class.students', 'class.students.parents'])->get();
        $timetableUrl = route('timetables.index'); // Or a more specific route if available
        foreach ($classSchedules as $schedule) {
            $class = $schedule->class;
            if (!$class) continue;
            foreach ($class->students as $student) {
                foreach ($student->parents as $parent) {
                    $parent->notify(new \App\Notifications\ParentTimetableNotification(
                        $student,
                        $class,
                        $schedule->day_of_week . ' ' . $schedule->start_time . '-' . $schedule->end_time,
                        $timetableUrl,
                        $action
                    ));
                }
            }
        }
    }
}
