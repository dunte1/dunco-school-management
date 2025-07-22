<?php

namespace App\Http\Controllers\Modules\Timetable\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Timetable\Models\RoomAvailability;
use Modules\Timetable\Models\Room;
use Illuminate\Http\Request;

class RoomAvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $availabilities = RoomAvailability::with('room')->paginate(20);
        return view('timetable::room_availabilities.index', compact('availabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::all();
        return view('timetable::room_availabilities.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        RoomAvailability::create($data);
        return redirect()->route('room_availabilities.index')->with('success', 'Room availability added.');
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
        $availability = RoomAvailability::findOrFail($id);
        $rooms = Room::all();
        return view('timetable::room_availabilities.edit', compact('availability', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $availability = RoomAvailability::findOrFail($id);
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        $availability->update($data);
        return redirect()->route('room_availabilities.index')->with('success', 'Room availability updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $availability = RoomAvailability::findOrFail($id);
        $availability->delete();
        return redirect()->route('room_availabilities.index')->with('success', 'Room availability deleted.');
    }
}
