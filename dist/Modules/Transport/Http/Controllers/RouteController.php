<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Models\Route;
use Modules\Transport\Models\RouteStop;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::with(['school'])
            ->when(request('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('start_location', 'like', "%{$search}%")
                      ->orWhere('end_location', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('transport::routes.index', compact('routes'));
    }

    public function create()
    {
        return view('transport::routes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'distance' => 'required|numeric|min:0',
            'estimated_time' => 'required|integer|min:1',
            'fare' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'stops' => 'nullable|array',
            'stops.*.stop_name' => 'required|string|max:255',
            'stops.*.stop_location' => 'required|string|max:255',
            'stops.*.stop_order' => 'required|integer|min:1',
            'stops.*.pickup_time' => 'nullable|date_format:H:i',
            'stops.*.drop_time' => 'nullable|date_format:H:i',
            'stops.*.latitude' => 'nullable|numeric|between:-90,90',
            'stops.*.longitude' => 'nullable|numeric|between:-180,180'
        ]);

        $route = Route::create($validated);

        if ($request->has('stops')) {
            foreach ($request->stops as $stop) {
                $route->stops()->create($stop);
            }
        }

        return redirect()->route('transport.routes.index')
            ->with('success', 'Route created successfully.');
    }

    public function show(Route $route)
    {
        $route->load(['stops', 'vehicles', 'trips']);
        
        return view('transport::routes.show', compact('route'));
    }

    public function edit(Route $route)
    {
        $route->load('stops');
        
        return view('transport::routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'distance' => 'required|numeric|min:0',
            'estimated_time' => 'required|integer|min:1',
            'fare' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'stops' => 'nullable|array',
            'stops.*.stop_name' => 'required|string|max:255',
            'stops.*.stop_location' => 'required|string|max:255',
            'stops.*.stop_order' => 'required|integer|min:1',
            'stops.*.pickup_time' => 'nullable|date_format:H:i',
            'stops.*.drop_time' => 'nullable|date_format:H:i',
            'stops.*.latitude' => 'nullable|numeric|between:-90,90',
            'stops.*.longitude' => 'nullable|numeric|between:-180,180'
        ]);

        $route->update($validated);

        // Update stops
        if ($request->has('stops')) {
            $route->stops()->delete();
            foreach ($request->stops as $stop) {
                $route->stops()->create($stop);
            }
        }

        return redirect()->route('transport.routes.index')
            ->with('success', 'Route updated successfully.');
    }

    public function destroy(Route $route)
    {
        $route->delete();
        
        return redirect()->route('transport.routes.index')
            ->with('success', 'Route deleted successfully.');
    }
} 
