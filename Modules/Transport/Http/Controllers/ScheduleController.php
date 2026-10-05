<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Schedule;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['route', 'vehicle', 'driver']);

        // Filter by route
        if ($request->has('route_id') && $request->route_id) {
            $query->where('route_id', $request->route_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('departure_time', $request->date);
        }

        // Search by schedule number
        if ($request->has('search') && $request->search) {
            $query->where('schedule_number', 'like', '%' . $request->search . '%');
        }

        $schedules = $query->orderBy('departure_time', 'asc')->paginate(15);
        $routes = \Modules\Transport\Models\Route::all();
        $vehicles = \Modules\Transport\Models\Vehicle::all();
        $drivers = \Modules\Transport\Models\Driver::all();

        return view('transport::schedules.index', compact('schedules', 'routes', 'vehicles', 'drivers'));
    }

    public function create()
    {
        $routes = \Modules\Transport\Models\Route::all();
        $vehicles = \Modules\Transport\Models\Vehicle::all();
        $drivers = \Modules\Transport\Models\Driver::all();
        return view('transport::schedules.create', compact('routes', 'vehicles', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_number' => 'required|string|max:50|unique:transport_schedules,schedule_number',
            'route_id' => 'required|exists:transport_routes,id',
            'vehicle_id' => 'required|exists:transport_vehicles,id',
            'driver_id' => 'required|exists:transport_drivers,id',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'capacity' => 'required|integer|min:1',
            'fare' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,active,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();

            Schedule::create($data);

            return redirect()->route('transport.schedules.index')
                           ->with('success', 'Schedule created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create schedule: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $schedule = Schedule::with(['route', 'vehicle', 'driver', 'bookings'])->findOrFail($id);
        return view('transport::schedules.show', compact('schedule'));
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $routes = \Modules\Transport\Models\Route::all();
        $vehicles = \Modules\Transport\Models\Vehicle::all();
        $drivers = \Modules\Transport\Models\Driver::all();
        return view('transport::schedules.edit', compact('schedule', 'routes', 'vehicles', 'drivers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'schedule_number' => 'required|string|max:50|unique:transport_schedules,schedule_number,' . $id,
            'route_id' => 'required|exists:transport_routes,id',
            'vehicle_id' => 'required|exists:transport_vehicles,id',
            'driver_id' => 'required|exists:transport_drivers,id',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'capacity' => 'required|integer|min:1',
            'fare' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,active,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->update($request->all());

            return redirect()->route('transport.schedules.index')
                           ->with('success', 'Schedule updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update schedule: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();

            return redirect()->route('transport.schedules.index')
                           ->with('success', 'Schedule deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete schedule: ' . $e->getMessage()]);
        }
    }
}