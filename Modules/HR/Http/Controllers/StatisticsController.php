<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Staff;
use Modules\HR\Models\Leave;
use Modules\HR\Models\Attendance;

class StatisticsController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $stats = [
            'total_employees' => Staff::count(),
            'active_employees' => Staff::where('status', 'active')->count(),
            'on_leave' => Leave::where('status', 'approved')->where('start_date', '<=', now())->where('end_date', '>=', now())->count(),
            'departments' => Staff::distinct('department_id')->count('department_id'),
            'pending_requests' => Leave::where('status', 'pending')->count(),
            'avg_attendance' => 0, // Will be calculated if attendance data exists
        ];

        // Get attendance statistics
        $attendance_stats = [
            'present_today' => 0,
            'absent_today' => 0,
            'late_arrivals' => 0,
            'early_departures' => 0,
        ];

        // Get leave statistics
        $leave_stats = [
            'pending' => Leave::where('status', 'pending')->count(),
            'approved' => Leave::where('status', 'approved')->count(),
            'rejected' => Leave::where('status', 'rejected')->count(),
            'total_days' => Leave::where('status', 'approved')->sum('days'),
            'avg_days' => Leave::where('status', 'approved')->avg('days') ?? 0,
        ];

        // Get department statistics
        $department_stats = Staff::with('department')
            ->selectRaw('department_id, COUNT(*) as total_staff')
            ->groupBy('department_id')
            ->get()
            ->map(function ($dept) {
                return (object) [
                    'name' => $dept->department->name ?? 'Unassigned',
                    'total_staff' => $dept->total_staff,
                    'present_today' => 0,
                    'on_leave' => 0,
                    'attendance_rate' => 0,
                    'avg_performance' => 0,
                ];
            });

        // Get monthly trends (last 6 months)
        $monthly_trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthly_trends[$date->format('M Y')] = [
                'attendance' => 0,
                'leaves' => Leave::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('days'),
            ];
        }

        return view('hr::statistics.index', compact(
            'stats',
            'attendance_stats',
            'leave_stats',
            'department_stats',
            'monthly_trends'
        ));
    }

    public function attendance()
    {
        return view('hr::statistics.attendance');
    }

    public function payroll()
    {
        return view('hr::statistics.payroll');
    }

    public function performance()
    {
        return view('hr::statistics.performance');
    }
}
