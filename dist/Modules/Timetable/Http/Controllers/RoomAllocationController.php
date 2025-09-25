<?php

namespace Modules\Timetable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Timetable\Models\RoomAllocation;
use Modules\Timetable\Http\Requests\RoomAllocationRequest;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class RoomAllocationController extends Controller
{
    public function index(Request $request)
    {
        $allocations = RoomAllocation::all();
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($allocations);
        }
        return view('room_allocations_index', compact('allocations'));
    }

    public function store(RoomAllocationRequest $request)
    {
        $allocation = RoomAllocation::create($request->validated());
        return response()->json($allocation, 201);
    }

    public function show($id)
    {
        $allocation = RoomAllocation::findOrFail($id);
        return response()->json($allocation);
    }

    public function update(RoomAllocationRequest $request, $id)
    {
        $allocation = RoomAllocation::findOrFail($id);
        $allocation->update($request->validated());
        return response()->json($allocation);
    }

    public function destroy($id)
    {
        $allocation = RoomAllocation::findOrFail($id);
        $allocation->delete();
        return response()->json(null, 204);
    }

    public function exportCsv()
    {
        $allocations = RoomAllocation::all();
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
        $allocations = RoomAllocation::all();
        $pdf = Pdf::loadView('Timetable::room_allocations_pdf', compact('allocations'));
        return $pdf->download('room_allocations.pdf');
    }

    /**
     * Auto-allocate rooms for class schedules (stub)
     * Params: class_schedule_ids[]
     * Returns: JSON message (not implemented)
     */
    public function autoAllocate(Request $request)
    {
        // Stub: In a real implementation, this would auto-allocate rooms
        return response()->json(['message' => 'Auto-allocation of rooms is not implemented yet.']);
    }

    /**
     * Check for room clashes/conflicts
     * Params: (optional) room_id, (optional) date
     * Returns: List of conflicts with color indicator
     */
    public function checkConflicts(Request $request)
    {
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
    }

    /**
     * View allocations per room (calendar-style)
     * Params: room_id
     * Returns: array[date][] = allocation
     */
    public function allocationsPerRoom(Request $request)
    {
        $roomId = $request->input('room_id');
        $allocations = RoomAllocation::where('room_id', $roomId)->orderBy('allocation_date')->get();
        $calendar = [];
        foreach ($allocations as $alloc) {
            $calendar[$alloc->allocation_date][] = $alloc;
        }
        return response()->json($calendar);
    }
} 