<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Models\Trip;
use Modules\Transport\Models\Vehicle;
use Modules\Transport\Models\Driver;
use Modules\Transport\Models\Route;
use Modules\Academic\app\Models\Student;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['vehicle', 'driver', 'route', 'school'])
            ->when(request('search'), function($query, $search) {
                $query->whereHas('vehicle', function($q) use ($search) {
                    $q->where('vehicle_number', 'like', "%{$search}%");
                })->orWhereHas('driver', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('route', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when(request('status'), function($query, $status) {
                $query->where('status', $status);
            })
            ->when(request('date'), function($query, $date) {
                $query->whereDate('trip_date', $date);
            })
            ->orderBy('trip_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        return view('transport::trips.index', compact('trips'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('status', 'active')->get();
        $drivers = Driver::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        $students = Student::with('academicClass')->get();
        
        return view('transport::trips.create', compact('vehicles', 'drivers', 'routes', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'route_id' => 'required|exists:routes,id',
            'trip_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'nullable|date_format:Y-m-d H:i|after:start_time',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'passenger_count' => 'nullable|integer|min:0',
            'fuel_consumed' => 'nullable|numeric|min:0',
            'distance_covered' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'passenger_ids' => 'nullable|array',
            'passenger_ids.*' => 'exists:students,id'
        ]);

        $trip = Trip::create($validated);

        if ($request->has('passenger_ids')) {
            $trip->passengers()->attach($request->passenger_ids);
        }

        return redirect()->route('transport.trips.index')
            ->with('success', 'Trip created successfully.');
    }

    public function show(Trip $trip)
    {
        $trip->load(['vehicle', 'driver', 'route', 'passengers.academicClass', 'school']);
        
        return view('transport::trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        $vehicles = Vehicle::where('status', 'active')->get();
        $drivers = Driver::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        $students = Student::with('academicClass')->get();
        
        return view('transport::trips.edit', compact('trip', 'vehicles', 'drivers', 'routes', 'students'));
    }

    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'route_id' => 'required|exists:routes,id',
            'trip_date' => 'required|date',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'nullable|date_format:Y-m-d H:i|after:start_time',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'passenger_count' => 'nullable|integer|min:0',
            'fuel_consumed' => 'nullable|numeric|min:0',
            'distance_covered' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'passenger_ids' => 'nullable|array',
            'passenger_ids.*' => 'exists:students,id'
        ]);

        $trip->update($validated);

        if ($request->has('passenger_ids')) {
            $trip->passengers()->sync($request->passenger_ids);
        } else {
            $trip->passengers()->detach();
        }

        return redirect()->route('transport.trips.index')
            ->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        
        return redirect()->route('transport.trips.index')
            ->with('success', 'Trip deleted successfully.');
    }
} 
