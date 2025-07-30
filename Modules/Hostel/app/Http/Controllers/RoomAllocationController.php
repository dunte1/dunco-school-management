<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\RoomAllocation;
use Modules\Hostel\Models\Bed;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Http\Requests\StoreRoomAllocationRequest;
use Modules\Hostel\Http\Requests\UpdateRoomAllocationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomAllocationController extends Controller
{
    public function index(Request $request)
    {
        $allocations = RoomAllocation::with(['bed.room.hostel', 'student'])
            ->when($request->input('status'), fn($q, $status) => $q->where('status', $status))
            ->when($request->input('hostel_id'), fn($q, $hostelId) => $q->whereHas('bed.room', fn($q2) => $q2->where('hostel_id', $hostelId)))
            ->when($request->input('student_id'), fn($q, $studentId) => $q->where('student_id', $studentId))
            ->latest()->paginate(15);
        $hostels = Hostel::all();
        $students = User::all();
        return view('hostel::room_allocations.index', compact('allocations', 'hostels', 'students'));
    }

    public function create()
    {
        $students = User::all();
        $beds = Bed::where('status', 'available')->get();
        $hostels = Hostel::all();
        return view('hostel::room_allocations.create', compact('students', 'beds', 'hostels'));
    }

    public function store(StoreRoomAllocationRequest $request)
    {
        $data = $request->validated();
        if ($data['allocation_type'] === 'auto') {
            $bed = $this->autoAllocateBed($data['student_id'], $data['preferences'] ?? []);
            if (!$bed) {
                return back()->withErrors(['bed_id' => 'No suitable bed found for auto-allocation.'])->withInput();
            }
            $data['bed_id'] = $bed->id;
        }
        $data['allocated_by'] = auth()->id();
        $allocation = RoomAllocation::create($data);
        // Optionally update bed status
        $allocation->bed->update(['status' => 'occupied', 'student_id' => $allocation->student_id]);
        \Modules\Hostel\Http\Controllers\HostelFeeController::generateFeeForAllocation($allocation);
        return redirect()->route('hostel.room_allocations.index')->with('success', 'Room allocated successfully.');
    }

    public function show(RoomAllocation $roomAllocation)
    {
        $roomAllocation->load(['bed.room.hostel', 'student']);
        return view('hostel::room_allocations.show', compact('roomAllocation'));
    }

    public function edit(RoomAllocation $roomAllocation)
    {
        $students = User::all();
        $beds = Bed::where('status', 'available')->orWhere('id', $roomAllocation->bed_id)->get();
        $hostels = Hostel::all();
        return view('hostel::room_allocations.edit', compact('roomAllocation', 'students', 'beds', 'hostels'));
    }

    public function update(UpdateRoomAllocationRequest $request, RoomAllocation $roomAllocation)
    {
        $data = $request->validated();
        if ($data['allocation_type'] === 'auto') {
            $bed = $this->autoAllocateBed($data['student_id'], $data['preferences'] ?? []);
            if (!$bed) {
                return back()->withErrors(['bed_id' => 'No suitable bed found for auto-allocation.'])->withInput();
            }
            $data['bed_id'] = $bed->id;
        }
        $roomAllocation->update($data);
        // Optionally update bed status
        $roomAllocation->bed->update(['status' => 'occupied', 'student_id' => $roomAllocation->student_id]);
        return redirect()->route('hostel.room_allocations.index')->with('success', 'Room allocation updated successfully.');
    }

    public function destroy(RoomAllocation $roomAllocation)
    {
        $roomAllocation->bed->update(['status' => 'available', 'student_id' => null]);
        $roomAllocation->delete();
        return redirect()->route('hostel.room_allocations.index')->with('success', 'Room allocation deleted successfully.');
    }

    /**
     * Auto-allocate a bed for a student based on preferences (gender, year, etc.)
     */
    protected function autoAllocateBed($studentId, $preferences = [])
    {
        $student = User::find($studentId);
        if (!$student) return null;
        // Example: filter beds by gender restriction and availability
        $beds = Bed::where('status', 'available')
            ->whereHas('room.hostel', function ($q) use ($student, $preferences) {
                if (isset($preferences['gender'])) {
                    $q->where('gender_restriction', $preferences['gender']);
                } else {
                    $q->where(function($q2) use ($student) {
                        $q2->where('gender_restriction', $student->gender ?? 'mixed')->orWhere('gender_restriction', 'mixed');
                    });
                }
                // Add more filters as needed (year, preferences, etc.)
            })
            ->first();
        return $beds;
    }
} 