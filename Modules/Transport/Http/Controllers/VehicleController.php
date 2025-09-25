<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Models\Vehicle;
use Modules\Transport\Models\Driver;
use Modules\Transport\Models\Route;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['driver', 'school'])
            ->when(request('search'), function($query, $search) {
                $query->where('vehicle_number', 'like', "%{$search}%")
                      ->orWhere('registration_number', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('transport::vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $drivers = Driver::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        
        return view('transport::vehicles.create', compact('drivers', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number',
            'vehicle_type' => 'required|in:bus,van,car,minibus',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'capacity' => 'required|integer|min:1',
            'driver_id' => 'nullable|exists:drivers,id',
            'status' => 'required|in:active,maintenance,inactive',
            'registration_number' => 'required|string|unique:vehicles,registration_number',
            'insurance_expiry' => 'required|date|after:today',
            'fitness_expiry' => 'required|date|after:today',
            'permit_expiry' => 'required|date|after:today',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'mileage' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'route_ids' => 'nullable|array',
            'route_ids.*' => 'exists:routes,id'
        ]);

        $vehicle = Vehicle::create($validated);
        
        if ($request->has('route_ids')) {
            $vehicle->routes()->attach($request->route_ids);
        }

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['driver', 'routes', 'trips']);
        
        return view('transport::vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $drivers = Driver::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        
        return view('transport::vehicles.edit', compact('vehicle', 'drivers', 'routes'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number,' . $vehicle->id,
            'vehicle_type' => 'required|in:bus,van,car,minibus',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'capacity' => 'required|integer|min:1',
            'driver_id' => 'nullable|exists:drivers,id',
            'status' => 'required|in:active,maintenance,inactive',
            'registration_number' => 'required|string|unique:vehicles,registration_number,' . $vehicle->id,
            'insurance_expiry' => 'required|date',
            'fitness_expiry' => 'required|date',
            'permit_expiry' => 'required|date',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'mileage' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'route_ids' => 'nullable|array',
            'route_ids.*' => 'exists:routes,id'
        ]);

        $vehicle->update($validated);
        
        if ($request->has('route_ids')) {
            $vehicle->routes()->sync($request->route_ids);
        } else {
            $vehicle->routes()->detach();
        }

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        
        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
} 
