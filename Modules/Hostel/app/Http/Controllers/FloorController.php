<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\Floor;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Http\Requests\StoreFloorRequest;
use Modules\Hostel\Http\Requests\UpdateFloorRequest;
use App\Http\Controllers\Controller;

class FloorController extends Controller
{
    public function index()
    {
        $floors = Floor::with('hostel')->paginate(15);
        return view('hostel::floors.index', compact('floors'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        return view('hostel::floors.create', compact('hostels'));
    }

    public function store(StoreFloorRequest $request)
    {
        Floor::create($request->validated());
        return redirect()->route('hostel.floors.index')->with('success', 'Floor created successfully.');
    }

    public function show(Floor $floor)
    {
        return view('hostel::floors.show', compact('floor'));
    }

    public function edit(Floor $floor)
    {
        $hostels = Hostel::all();
        return view('hostel::floors.edit', compact('floor', 'hostels'));
    }

    public function update(UpdateFloorRequest $request, Floor $floor)
    {
        $floor->update($request->validated());
        return redirect()->route('hostel.floors.index')->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor)
    {
        $floor->delete();
        return redirect()->route('hostel.floors.index')->with('success', 'Floor deleted successfully.');
    }
} 