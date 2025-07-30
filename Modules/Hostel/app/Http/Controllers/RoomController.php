<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Floor;
use Modules\Hostel\Http\Requests\StoreRoomRequest;
use Modules\Hostel\Http\Requests\UpdateRoomRequest;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['hostel', 'floor'])->paginate(15);
        return view('hostel::rooms.index', compact('rooms'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        $floors = Floor::all();
        return view('hostel::rooms.create', compact('hostels', 'floors'));
    }

    public function store(StoreRoomRequest $request)
    {
        Room::create($request->validated());
        return redirect()->route('hostel.rooms.index')->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load(['hostel', 'floor']);
        return view('hostel::rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $hostels = Hostel::all();
        $floors = Floor::all();
        return view('hostel::rooms.edit', compact('room', 'hostels', 'floors'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());
        return redirect()->route('hostel.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('hostel.rooms.index')->with('success', 'Room deleted successfully.');
    }
} 