<?php

namespace Modules\Timetable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Modules\Timetable\Models\RoomAllocation;
use Modules\Timetable\Http\Requests\RoomAllocationRequest;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class RoomAllocationController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (Schema::hasTable('room_allocations')) {
                $allocations = RoomAllocation::all();
            } else {
                $allocations = collect();
            }
        } catch (\Exception $e) {
            $allocations = collect();
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($allocations);
        }
        return view('timetable::room_allocations_index', compact('allocations'));
    }

    public function store(RoomAllocationRequest $request)
    {
        try {
            if (!Schema::hasTable('room_allocations')) {
                return response()->json(['error' => 'Room allocations table does not exist.'], 500);
            }
            $allocation = RoomAllocation::create($request->validated());
            return response()->json($allocation, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create room allocation: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            if (!Schema::hasTable('room_allocations')) {
                return response()->json(['error' => 'Room allocations table does not exist.'], 500);
            }
            $allocation = RoomAllocation::findOrFail($id);
            return response()->json($allocation);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Room allocation not found.'], 404);
        }
    }

    public function update(RoomAllocationRequest $request, $id)
    {
        try {
            if (!Schema::hasTable('room_allocations')) {
                return response()->json(['error' => 'Room allocations table does not exist.'], 500);
            }
            $allocation = RoomAllocation::findOrFail($id);
            $allocation->update($request->validated());
            return response()->json($allocation);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update room allocation: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            if (!Schema::hasTable('room_allocations')) {
                return response()->json(['error' => 'Room allocations table does not exist.'], 500);
            }
            $allocation = RoomAllocation::findOrFail($id);
            $allocation->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete room allocation: ' . $e->getMessage()], 500);
        }
    }

    public function exportCsv()
    {
        try {
            if (Schema::hasTable('room_allocations')) {
                $allocations = RoomAllocation::all();
            } else {
                $allocations = collect();
            }
        } catch (\Exception $e) {
            $allocations = collect();
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="room_allocations.csv"',
        ];
        $columns = ['id', 'room_id', 'class_schedule_id', 'allocation_date'];
        $callback = function() use ($allocations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($allocations as $a) {
                fputcsv($file, [
                    $a->id,
                    $a->room_id,
                    $a->class_schedule_id,
                    $a->allocation_date,
                ]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        try {
            if (Schema::hasTable('room_allocations')) {
                $allocations = RoomAllocation::all();
            } else {
                $allocations = collect();
            }
        } catch (\Exception $e) {
            $allocations = collect();
        }

        $pdf = Pdf::loadView('timetable::room_allocations_pdf', compact('allocations'));
        return $pdf->download('room_allocations.pdf');
    }

    /**
     * Auto-allocate rooms for class schedules.
     * Assigns the best available room to each schedule based on capacity and availability.
     */
    public function autoAllocate(Request $request)
    {
        try {
            if (!Schema::hasTable('room_allocations') || !Schema::hasTable('class_schedules')) {
                return response()->json(['error' => 'Required tables do not exist.'], 500);
            }

            $scheduleIds = $request->input('class_schedule_ids', []);

            // If no specific IDs, allocate all unassigned schedules
            $query = \DB::table('class_schedules')->whereNull('room_id');
            if (!empty($scheduleIds)) {
                $query->whereIn('id', $scheduleIds);
            }
            $schedules = $query->get();

            if ($schedules->isEmpty()) {
                return response()->json([
                    'message' => 'No unassigned class schedules found.',
                    'allocated' => 0,
                ]);
            }

            $rooms = Room::orderBy('capacity', 'desc')->get();
            if ($rooms->isEmpty()) {
                return response()->json(['error' => 'No rooms available for allocation.'], 404);
            }

            $allocated = 0;
            $conflicts = 0;

            foreach ($schedules as $schedule) {
                $dayOfWeek = $schedule->day_of_week;
                $startTime = $schedule->start_time;
                $endTime = $schedule->end_time;

                foreach ($rooms as $room) {
                    // Check if this room is already allocated to another schedule at the same time
                    $hasConflict = \DB::table('room_allocations')
                        ->join('class_schedules', 'room_allocations.class_schedule_id', '=', 'class_schedules.id')
                        ->where('room_allocations.room_id', $room->id)
                        ->where('class_schedules.day_of_week', $dayOfWeek)
                        ->where('class_schedules.start_time', '<', $endTime)
                        ->where('class_schedules.end_time', '>', $startTime)
                        ->exists();

                    if (!$hasConflict) {
                        // Create the allocation
                        \DB::table('room_allocations')->insert([
                            'room_id' => $room->id,
                            'class_schedule_id' => $schedule->id,
                            'allocation_date' => now()->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Update the schedule's room_id
                        \DB::table('class_schedules')->where('id', $schedule->id)->update([
                            'room_id' => $room->id,
                            'updated_at' => now(),
                        ]);

                        $allocated++;
                        break;
                    }
                }
            }

            return response()->json([
                'message' => "Auto-allocation complete. {$allocated} schedules allocated.",
                'allocated' => $allocated,
                'total_checked' => $schedules->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Auto-allocation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check for room clashes/conflicts
     * Params: (optional) room_id, (optional) date
     * Returns: List of conflicts with color indicator
     */
    public function checkConflicts(Request $request)
    {
        try {
            if (!Schema::hasTable('room_allocations')) {
                return response()->json([]);
            }

            $roomId = $request->input('room_id');
            $query = RoomAllocation::query();
            if ($roomId) {
                $query->where('room_id', $roomId);
            }
            $allocations = $query->orderBy('allocation_date')->get();
            $conflicts = [];
            $byDate = $allocations->groupBy('allocation_date');
            foreach ($byDate as $date => $allocs) {
                $byTime = $allocs->groupBy('room_id');
                foreach ($byTime as $roomId => $roomAllocs) {
                    $sorted = $roomAllocs->sortBy('class_schedule_id');
                    $prev = null;
                    foreach ($sorted as $alloc) {
                        if ($prev && $alloc->allocation_date == $prev->allocation_date) {
                            $conflicts[] = [
                                'room_id' => $roomId,
                                'allocation_date' => $date,
                                'conflict' => [$prev->id, $alloc->id],
                                'color' => 'red', // red for conflict
                            ];
                        }
                        $prev = $alloc;
                    }
                }
            }
            return response()->json($conflicts);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * View allocations per room (calendar-style)
     * Params: room_id
     * Returns: array[date][] = allocation
     */
    public function allocationsPerRoom(Request $request)
    {
        try {
            if (!Schema::hasTable('room_allocations')) {
                return response()->json([]);
            }

            $roomId = $request->input('room_id');
            $allocations = RoomAllocation::where('room_id', $roomId)->orderBy('allocation_date')->get();
            $calendar = [];
            foreach ($allocations as $alloc) {
                $calendar[$alloc->allocation_date][] = $alloc;
            }
            return response()->json($calendar);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
} 