<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Staff;
use Modules\HR\Models\Leave;
use Modules\HR\Models\LeaveType;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with(['staff', 'leaveType']);
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $leaves = $query->orderBy('start_date', 'desc')->paginate(30);
        $staff = Staff::all();
        $types = LeaveType::all();
        return view('hr::leave.index', compact('leaves', 'staff', 'types'));
    }

    public function create()
    {
        $staff = Staff::all();
        $types = LeaveType::all();
        return view('hr::leave.create', compact('staff', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable',
        ]);
        $data['status'] = 'pending';
        $data['days'] = Carbon::parse($data['end_date'])->diffInDays(Carbon::parse($data['start_date'])) + 1;
        Leave::create($data);
        // TODO: Send notification/email
        return redirect()->route('hr.leave.index')->with('success', 'Leave application submitted.');
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->status = 'approved';
        $leave->approved_by = auth()->id();
        $leave->approved_at = now();
        $leave->save();
        // TODO: Send notification/email
        return redirect()->route('hr.leave.index')->with('success', 'Leave approved.');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->status = 'rejected';
        $leave->approved_by = auth()->id();
        $leave->approved_at = now();
        $leave->save();
        // TODO: Send notification/email
        return redirect()->route('hr.leave.index')->with('success', 'Leave rejected.');
    }

    public function balances(Request $request)
    {
        $staff = Staff::all();
        $types = LeaveType::all();
        // TODO: Calculate balances
        return view('hr::leave.balances', compact('staff', 'types'));
    }
} 