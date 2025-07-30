<?php

namespace Modules\Hostel\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Hostel\Models\Bed;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Http\Requests\StoreBedRequest;
use Modules\Hostel\Http\Requests\UpdateBedRequest;

class BedController extends Controller
{
    public function index()
    {
        $beds = Bed::with(['room', 'room.hostel'])->paginate(15);
        return view('hostel::beds.index', compact('beds'));
    }

    public function create()
    {
        $rooms = Room::with('hostel')->get();
        return view('hostel::beds.create', compact('rooms'));
    }

    public function store(StoreBedRequest $request)
    {
        Bed::create($request->validated());
        return redirect()->route('hostel.beds.index')->with('success', 'Bed created successfully.');
    }

    public function show(Bed $bed)
    {
        $bed->load(['room', 'room.hostel']);
        return view('hostel::beds.show', compact('bed'));
    }

    public function edit(Bed $bed)
    {
        $rooms = Room::with('hostel')->get();
        return view('hostel::beds.edit', compact('bed', 'rooms'));
    }

    public function update(UpdateBedRequest $request, Bed $bed)
    {
        $bed->update($request->validated());
        return redirect()->route('hostel.beds.index')->with('success', 'Bed updated successfully.');
    }

    public function destroy(Bed $bed)
    {
        $bed->delete();
        return redirect()->route('hostel.beds.index')->with('success', 'Bed deleted successfully.');
    }
} 