<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Overtime;
use Modules\HR\Models\Staff;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Overtime::with(['staff']);

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

        $overtimes = $query->orderBy('date', 'desc')->paginate(15);
        $staff = Staff::all();

        return view('hr::overtime.index', compact('overtimes', 'staff'));
    }

    public function create()
    {
        $staff = Staff::all();
        return view('hr::overtime.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0|max:24',
            'rate' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'is_approved' => 'boolean'
        ]);

        Overtime::create($request->all());

        return redirect()->route('hr.overtime.index')
                        ->with('success', 'Overtime record created successfully.');
    }

    public function show($id)
    {
        $overtime = Overtime::with(['staff'])->findOrFail($id);
        return view('hr::overtime.show', compact('overtime'));
    }

    public function edit($id)
    {
        $overtime = Overtime::findOrFail($id);
        $staff = Staff::all();
        return view('hr::overtime.edit', compact('overtime', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0|max:24',
            'rate' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'is_approved' => 'boolean'
        ]);

        $overtime = Overtime::findOrFail($id);
        $overtime->update($request->all());

        return redirect()->route('hr.overtime.index')
                        ->with('success', 'Overtime record updated successfully.');
    }

    public function destroy($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->delete();

        return redirect()->route('hr.overtime.index')
                        ->with('success', 'Overtime record deleted successfully.');
    }
}
