<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
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
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $days = Carbon::parse($data['start_date'])->diffInDays(Carbon::parse($data['end_date'])) + 1;

        $leaveType = LeaveType::where('name', $data['type'])->first();
        if ($leaveType) {
            $usedDays = Leave::where('staff_id', $data['staff_id'])
                ->where('type', $data['type'])
                ->whereYear('start_date', now()->year)
                ->where('status', 'approved')
                ->sum('days');

            $remaining = $leaveType->default_days - $usedDays;
            if ($days > $remaining) {
                return back()->withErrors([
                    'end_date' => "Insufficient leave balance. You have {$remaining} days remaining for {$leaveType->name}.",
                ])->withInput();
            }
        }

        $data['status'] = 'pending';
        $data['days'] = $days;
        $leave = Leave::create($data);

        $staffMember = Staff::find($data['staff_id']);
        if ($staffMember && $staffMember->email) {
            try {
                Mail::raw("Dear {$staffMember->first_name},\n\nYour leave application for {$data['type']} from {$data['start_date']} to {$data['end_date']} ({$days} days) has been submitted and is pending approval.\n\nReason: {$data['reason']}\n\nRegards,\nHR Department", function ($message) use ($staffMember) {
                    $message->to($staffMember->email)
                        ->subject('Leave Application Submitted');
                });
            } catch (\Throwable $e) {
                // Continue without failing if email cannot be sent
            }
        }

        return redirect()->route('hr.leave.index')->with('success', 'Leave application submitted.');
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending leave applications can be approved.');
        }

        $leaveType = LeaveType::where('name', $leave->type)->first();
        if ($leaveType) {
            $usedDays = Leave::where('staff_id', $leave->staff_id)
                ->where('type', $leave->type)
                ->whereYear('start_date', now()->year)
                ->where('status', 'approved')
                ->where('id', '!=', $leave->id)
                ->sum('days');

            $remaining = $leaveType->default_days - $usedDays;
            if ($leave->days > $remaining) {
                return back()->with('error', "Insufficient leave balance. Staff has only {$remaining} days remaining for {$leaveType->name}.");
            }
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $staffMember = $leave->staff;
        if ($staffMember && $staffMember->email) {
            try {
                Mail::raw("Dear {$staffMember->first_name},\n\nYour leave application for {$leave->type} from {$leave->start_date} to {$leave->end_date} ({$leave->days} days) has been approved.\n\nApproved by: " . (auth()->user()->name ?? 'Administrator') . "\n\nRegards,\nHR Department", function ($message) use ($staffMember) {
                    $message->to($staffMember->email)
                        ->subject('Leave Application Approved');
                });
            } catch (\Throwable $e) {
            }
        }

        return redirect()->route('hr.leave.index')->with('success', 'Leave approved.');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending leave applications can be rejected.');
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $staffMember = $leave->staff;
        if ($staffMember && $staffMember->email) {
            try {
                Mail::raw("Dear {$staffMember->first_name},\n\nYour leave application for {$leave->type} from {$leave->start_date} to {$leave->end_date} ({$leave->days} days) has been rejected.\n\nRegards,\nHR Department", function ($message) use ($staffMember) {
                    $message->to($staffMember->email)
                        ->subject('Leave Application Rejected');
                });
            } catch (\Throwable $e) {
            }
        }

        return redirect()->route('hr.leave.index')->with('success', 'Leave rejected.');
    }

    public function balances(Request $request)
    {
        $staffId = $request->input('staff_id');
        $types = LeaveType::active()->get();
        $allStaff = Staff::all();

        $balances = collect();

        if ($staffId) {
            $staff = Staff::findOrFail($staffId);
            $balances = $this->calculateBalances($staff, $types);
        } else {
            foreach ($allStaff as $staffMember) {
                $balances->push([
                    'staff' => $staffMember,
                    'balances' => $this->calculateBalances($staffMember, $types),
                ]);
            }
        }

        return view('hr::leave.balances', compact('balances', 'types', 'allStaff', 'staffId'));
    }

    protected function calculateBalances(Staff $staff, $types): array
    {
        $result = [];

        foreach ($types as $type) {
            $approvedDays = Leave::where('staff_id', $staff->id)
                ->where('type', $type->name)
                ->whereYear('start_date', now()->year)
                ->where('status', 'approved')
                ->sum('days');

            $pendingDays = Leave::where('staff_id', $staff->id)
                ->where('type', $type->name)
                ->whereYear('start_date', now()->year)
                ->where('status', 'pending')
                ->sum('days');

            $default = $type->default_days;
            $used = (int) $approvedDays;
            $remaining = max(0, $default - $used);

            $result[] = [
                'type' => $type->name,
                'default_days' => $default,
                'used' => $used,
                'pending' => (int) $pendingDays,
                'remaining' => $remaining,
            ];
        }

        return $result;
    }
}
