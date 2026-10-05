<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Staff;
use Modules\HR\Models\Leave;
use Modules\HR\Models\Attendance;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Get key metrics
        $metrics = [
            'attendance_rate' => 0, // Will be calculated if attendance data exists
            'productivity_score' => 0,
            'overtime_hours' => 0,
            'cost_per_employee' => 0,
        ];

        // Get top performers (mock data for now)
        $top_performers = Staff::take(5)->get()->map(function ($staff, $index) {
            return (object) [
                'name' => $staff->first_name . ' ' . $staff->last_name,
                'department' => $staff->department ?? 'Unassigned',
                'score' => rand(70, 100),
                'attendance' => rand(80, 100),
                'overtime' => rand(0, 20),
            ];
        });

        return view('hr::analytics.index', compact('metrics', 'top_performers'));
    }

    public function attendance()
    {
        return view('hr::analytics.attendance');
    }

    public function payroll()
    {
        return view('hr::analytics.payroll');
    }

    public function performance()
    {
        return view('hr::analytics.performance');
    }
}
