<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Staff;
use Modules\HR\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('staff');
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $attendance = $query->orderBy('date', 'desc')->paginate(30);
        $staff = Staff::all();
        return view('hr::attendance.index', compact('attendance', 'staff'));
    }

    public function create()
    {
        $staff = Staff::all();
        return view('hr::attendance.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'status' => 'required',
            'remarks' => 'nullable',
        ]);
        Attendance::create($data);
        return redirect()->route('hr.attendance.index')->with('success', 'Attendance recorded.');
    }

    public function report(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $staff = Staff::all();
        $attendance = Attendance::where('date', 'like', $month.'%')->get();
        return view('hr::attendance.report', compact('attendance', 'staff', 'month'));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);
        $today = now()->toDateString();
        $alreadyClockedIn = Attendance::where('staff_id', $request->staff_id)
            ->where('date', $today)
            ->exists();
        if ($alreadyClockedIn) {
            return redirect()->back()->with('error', 'You have already clocked in today.');
        }
        Attendance::create([
            'staff_id' => $request->staff_id,
            'date' => $today,
            'clock_in' => now()->toTimeString(),
            'status' => 'present',
        ]);
        return redirect()->back()->with('success', 'Clock-in successful.');
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);
        $today = now()->toDateString();
        $attendance = Attendance::where('staff_id', $request->staff_id)
            ->where('date', $today)
            ->first();
        if (!$attendance) {
            return redirect()->back()->with('error', 'You must clock in before clocking out.');
        }
        if ($attendance->clock_out) {
            return redirect()->back()->with('error', 'You have already clocked out today.');
        }
        $attendance->update([
            'clock_out' => now()->toTimeString(),
        ]);
        return redirect()->back()->with('success', 'Clock-out successful.');
    }

    /**
     * API: Get staff attendance analytics (monthly/yearly, chart-ready)
     */
    public function getStaffAnalytics(Request $request)
    {
        $departmentId = $request->get('department_id');
        $type = $request->get('type', 'monthly'); // 'monthly' or 'yearly'
        $year = $request->get('year', now()->year);
        $statuses = ['present', 'absent', 'late', 'excused', 'sick', 'on_leave', 'suspended'];

        $query = Attendance::query();
        if ($departmentId) {
            $query->whereHas('staff', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }
        $query->whereYear('date', $year);

        $groupBy = $type === 'yearly' ? DB::raw('YEAR(date)') : DB::raw('MONTH(date)');
        $select = $type === 'yearly'
            ? [DB::raw('YEAR(date) as period')]
            : [DB::raw('MONTH(date) as period')];
        foreach ($statuses as $status) {
            $select[] = DB::raw("SUM(CASE WHEN status = '$status' THEN 1 ELSE 0 END) as $status");
        }
        $analytics = $query->select($select)
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Prepare chart data
        $labels = $analytics->pluck('period')->map(function($p) use ($type, $year) {
            return $type === 'yearly' ? $p : Carbon::create()->month($p)->format('M');
        });
        $datasets = [];
        foreach ($statuses as $status) {
            $datasets[] = [
                'label' => ucfirst($status),
                'data' => $analytics->pluck($status),
            ];
        }
        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }
} 