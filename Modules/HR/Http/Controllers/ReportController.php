<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Staff;
use Modules\HR\Models\Leave;
use Modules\HR\Models\Attendance;

class ReportController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $stats = [
            'total_staff' => Staff::count(),
            'present_today' => 0, // Will be calculated if attendance data exists
            'on_leave' => Leave::where('status', 'approved')->where('start_date', '<=', now())->where('end_date', '>=', now())->count(),
            'pending_approvals' => Leave::where('status', 'pending')->count(),
        ];

        // Get recent activities (mock data for now)
        $recent_activities = collect([
            (object) [
                'created_at' => now()->subHours(2),
                'description' => 'New leave application submitted',
                'staff_name' => 'John Doe',
                'status' => 'pending',
            ],
            (object) [
                'created_at' => now()->subHours(4),
                'description' => 'Timesheet approved',
                'staff_name' => 'Jane Smith',
                'status' => 'completed',
            ],
            (object) [
                'created_at' => now()->subHours(6),
                'description' => 'Overtime request processed',
                'staff_name' => 'Mike Johnson',
                'status' => 'completed',
            ],
        ]);

        return view('hr::reports.index', compact('stats', 'recent_activities'));
    }

    public function attendance()
    {
        return view('hr::reports.attendance');
    }

    public function payroll()
    {
        return view('hr::reports.payroll');
    }

    public function leave()
    {
        return view('hr::reports.leave');
    }

    public function performance()
    {
        return view('hr::reports.performance');
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'attendance');
        
        // Basic export functionality
        return response()->json([
            'message' => "Exporting {$type} report...",
            'type' => $type
        ]);
    }
}
