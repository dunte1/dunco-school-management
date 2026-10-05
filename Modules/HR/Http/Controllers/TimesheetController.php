<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Timesheet;
use Modules\HR\Models\Staff;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $query = Timesheet::with(['staff']);

        // Filter by staff
        if ($request->has('staff_id') && $request->staff_id) {
            $query->where('staff_id', $request->staff_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $timesheets = $query->orderBy('date', 'desc')->paginate(15);
        $staff = Staff::all();

        return view('hr::timesheets.index', compact('timesheets', 'staff'));
    }

    public function create()
    {
        $staff = Staff::all();
        return view('hr::timesheets.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'hours_worked' => 'required|numeric|min:0|max:24',
            'description' => 'nullable|string',
            'is_approved' => 'boolean'
        ]);

        Timesheet::create($request->all());

        return redirect()->route('hr.timesheets.index')
                        ->with('success', 'Timesheet created successfully.');
    }

    public function show($id)
    {
        $timesheet = Timesheet::with(['staff'])->findOrFail($id);
        return view('hr::timesheets.show', compact('timesheet'));
    }

    public function edit($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $staff = Staff::all();
        return view('hr::timesheets.edit', compact('timesheet', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'hours_worked' => 'required|numeric|min:0|max:24',
            'description' => 'nullable|string',
            'is_approved' => 'boolean'
        ]);

        $timesheet = Timesheet::findOrFail($id);
        $timesheet->update($request->all());

        return redirect()->route('hr.timesheets.index')
                        ->with('success', 'Timesheet updated successfully.');
    }

    public function destroy($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->delete();

        return redirect()->route('hr.timesheets.index')
                        ->with('success', 'Timesheet deleted successfully.');
    }
}
