<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\HostelFee;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\Bed;
use Modules\Hostel\Models\RoomAllocation;
use Modules\Hostel\Http\Requests\StoreHostelFeeRequest;
use Modules\Hostel\Http\Requests\UpdateHostelFeeRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class HostelFeeController extends Controller
{
    public function index(Request $request)
    {
        $fees = HostelFee::with(['hostel', 'room', 'bed', 'student'])
            ->when($request->input('status'), fn($q, $status) => $q->where('status', $status))
            ->when($request->input('hostel_id'), fn($q, $hostelId) => $q->where('hostel_id', $hostelId))
            ->when($request->input('student_id'), fn($q, $studentId) => $q->where('student_id', $studentId))
            ->latest()->paginate(15);
        $hostels = Hostel::all();
        $students = User::all();
        return view('hostel::fees.index', compact('fees', 'hostels', 'students'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        $rooms = Room::all();
        $beds = Bed::all();
        $students = User::all();
        return view('hostel::fees.create', compact('hostels', 'rooms', 'beds', 'students'));
    }

    public function store(StoreHostelFeeRequest $request)
    {
        $data = $request->validated();
        HostelFee::create($data);
        return redirect()->route('hostel.fees.index')->with('success', 'Hostel fee created successfully.');
    }

    public function show(HostelFee $hostelFee)
    {
        $hostelFee->load(['hostel', 'room', 'bed', 'student']);
        return view('hostel::fees.show', compact('hostelFee'));
    }

    public function edit(HostelFee $hostelFee)
    {
        $hostels = Hostel::all();
        $rooms = Room::all();
        $beds = Bed::all();
        $students = User::all();
        return view('hostel::fees.edit', compact('hostelFee', 'hostels', 'rooms', 'beds', 'students'));
    }

    public function update(UpdateHostelFeeRequest $request, HostelFee $hostelFee)
    {
        $data = $request->validated();
        $hostelFee->update($data);
        return redirect()->route('hostel.fees.index')->with('success', 'Hostel fee updated successfully.');
    }

    public function destroy(HostelFee $hostelFee)
    {
        $hostelFee->delete();
        return redirect()->route('hostel.fees.index')->with('success', 'Hostel fee deleted successfully.');
    }

    /**
     * Automatically generate a hostel fee when a room allocation is created.
     */
    public static function generateFeeForAllocation(RoomAllocation $allocation)
    {
        $amount = $allocation->bed->price ?? $allocation->room->price_per_bed ?? 0;
        HostelFee::create([
            'hostel_id' => $allocation->bed->room->hostel_id,
            'room_id' => $allocation->bed->room_id,
            'bed_id' => $allocation->bed_id,
            'student_id' => $allocation->student_id,
            'amount' => $amount,
            'status' => 'unpaid',
            'due_date' => now()->addMonth(),
            'notes' => 'Auto-generated on allocation',
        ]);
    }
} 