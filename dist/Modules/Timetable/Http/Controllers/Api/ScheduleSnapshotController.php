<?php

namespace Modules\Timetable\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Timetable\Models\ScheduleSnapshot;
use Modules\Timetable\Models\ClassSchedule;

class ScheduleSnapshotController extends Controller
{
    /**
     * List schedule snapshots (optionally filter by schedule_id or timetable_id)
     */
    public function index(Request $request)
    {
        $query = ScheduleSnapshot::query();
        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->input('schedule_id'));
        }
        if ($request->filled('timetable_id')) {
            $query->where('timetable_id', $request->input('timetable_id'));
        }
        $snapshots = $query->latest()->paginate(20);
        return response()->json($snapshots);
    }

    /**
     * View a single snapshot
     */
    public function show($id)
    {
        $snapshot = ScheduleSnapshot::findOrFail($id);
        return response()->json($snapshot);
    }

    /**
     * Restore a snapshot (undo/rollback)
     */
    public function restore(Request $request, $id)
    {
        $snapshot = ScheduleSnapshot::findOrFail($id);
        $data = $snapshot->data;
        if ($snapshot->action === 'delete') {
            // Restore deleted schedule
            $schedule = ClassSchedule::create($data);
        } else {
            // Update or create
            $schedule = ClassSchedule::find($snapshot->schedule_id);
            if ($schedule) {
                $schedule->update($data);
            } else {
                $schedule = ClassSchedule::create($data);
            }
        }
        return response()->json(['success' => true, 'restored_schedule' => $schedule]);
    }
} 