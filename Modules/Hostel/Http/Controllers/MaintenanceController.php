<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Hostel\Models\Maintenance;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with(['room', 'assignedTo']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Search by description
        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $maintenance = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('hostel::maintenance.index', compact('maintenance'));
    }

    public function create()
    {
        $rooms = \Modules\Hostel\Models\Room::all();
        $staff = \App\Models\User::where('role', 'maintenance')->get();
        return view('hostel::maintenance.create', compact('rooms', 'staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to_id' => 'nullable|exists:users,id',
            'estimated_cost' => 'nullable|numeric|min:0',
            'scheduled_date' => 'nullable|date|after:today'
        ]);

        try {
            $data = $request->all();
            $data['status'] = 'pending';
            $data['reported_by'] = auth()->id();

            Maintenance::create($data);

            return redirect()->route('hostel.maintenance.index')
                           ->with('success', 'Maintenance request created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create maintenance request: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $maintenance = Maintenance::with(['room', 'assignedTo', 'reportedBy'])->findOrFail($id);
        return view('hostel::maintenance.show', compact('maintenance'));
    }

    public function edit($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $rooms = \Modules\Hostel\Models\Room::all();
        $staff = \App\Models\User::where('role', 'maintenance')->get();
        return view('hostel::maintenance.edit', compact('maintenance', 'rooms', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'assigned_to_id' => 'nullable|exists:users,id',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'scheduled_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        try {
            $maintenance = Maintenance::findOrFail($id);
            $maintenance->update($request->all());

            return redirect()->route('hostel.maintenance.index')
                           ->with('success', 'Maintenance request updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update maintenance request: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $maintenance = Maintenance::findOrFail($id);
            $maintenance->delete();

            return redirect()->route('hostel.maintenance.index')
                           ->with('success', 'Maintenance request deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete maintenance request: ' . $e->getMessage()]);
        }
    }
}