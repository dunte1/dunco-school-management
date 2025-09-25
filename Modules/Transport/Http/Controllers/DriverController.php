<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Models\Driver;
use Modules\Transport\Models\Vehicle;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with(['school'])
            ->when(request('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('license_number', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('transport::drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('transport::drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:drivers,license_number',
            'license_expiry' => 'required|date|after:today',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:drivers,email',
            'address' => 'required|string',
            'date_of_birth' => 'required|date|before:today',
            'joining_date' => 'required|date|before_or_equal:today',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'emergency_contact' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:5',
            'experience_years' => 'nullable|integer|min:0|max:50'
        ]);

        Driver::create($validated);

        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    public function show(Driver $driver)
    {
        $driver->load(['vehicles', 'trips']);
        
        return view('transport::drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        return view('transport::drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
            'license_expiry' => 'required|date',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:drivers,email,' . $driver->id,
            'address' => 'required|string',
            'date_of_birth' => 'required|date|before:today',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'emergency_contact' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:5',
            'experience_years' => 'nullable|integer|min:0|max:50'
        ]);

        $driver->update($validated);

        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        
        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }
} 
