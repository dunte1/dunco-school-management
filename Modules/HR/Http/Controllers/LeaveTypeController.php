<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\LeaveType;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::orderBy('name')->paginate(20);
        return view('hr::leave-type.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('hr::leave-type.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'description' => 'nullable|string',
            'default_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        LeaveType::create($data);

        return redirect()->route('hr.leave-type.index')
            ->with('success', 'Leave type created successfully.');
    }

    public function show($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        return view('hr::leave-type.show', compact('leaveType'));
    }

    public function edit($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        return view('hr::leave-type.edit', compact('leaveType'));
    }

    public function update(Request $request, $id)
    {
        $leaveType = LeaveType::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $id,
            'description' => 'nullable|string',
            'default_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $leaveType->update($data);

        return redirect()->route('hr.leave-type.index')
            ->with('success', 'Leave type updated successfully.');
    }

    public function destroy($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        
        // Check if this leave type is being used
        if ($leaveType->leaves()->count() > 0) {
            return redirect()->route('hr.leave-type.index')
                ->with('error', 'Cannot delete leave type that is being used by leave applications.');
        }

        $leaveType->delete();

        return redirect()->route('hr.leave-type.index')
            ->with('success', 'Leave type deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        $leaveType->update(['is_active' => !$leaveType->is_active]);

        $status = $leaveType->is_active ? 'activated' : 'deactivated';
        return redirect()->route('hr.leave-type.index')
            ->with('success', "Leave type {$status} successfully.");
    }
}
