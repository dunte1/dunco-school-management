<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\Student;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_hostels' => Hostel::count(),
            'total_rooms' => Room::count(),
            'total_students' => Student::count(),
            'occupied_rooms' => Room::where('status', 'occupied')->count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'occupancy_rate' => Room::count() > 0 ? (Room::where('status', 'occupied')->count() / Room::count()) * 100 : 0,
        ];

        return view('hostel::statistics.index', compact('stats'));
    }

    public function occupancy()
    {
        $occupancy = [
            'by_hostel' => Hostel::withCount(['rooms', 'students'])->get(),
            'by_floor' => Room::selectRaw('floor_id, COUNT(*) as total_rooms, SUM(CASE WHEN status = "occupied" THEN 1 ELSE 0 END) as occupied_rooms')
                ->groupBy('floor_id')
                ->get(),
            'monthly_trends' => Student::selectRaw('MONTH(check_in_date) as month, COUNT(*) as check_ins')
                ->whereYear('check_in_date', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];

        return view('hostel::statistics.occupancy', compact('occupancy'));
    }

    public function revenue()
    {
        $revenue = [
            'total_fees' => \Modules\Hostel\Models\HostelFee::sum('amount'),
            'collected_fees' => \Modules\Hostel\Models\HostelFee::where('status', 'paid')->sum('amount'),
            'pending_fees' => \Modules\Hostel\Models\HostelFee::where('status', 'pending')->sum('amount'),
            'monthly_revenue' => \Modules\Hostel\Models\HostelFee::selectRaw('MONTH(due_date) as month, SUM(amount) as total')
                ->whereYear('due_date', date('Y'))
                ->where('status', 'paid')
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];

        return view('hostel::statistics.revenue', compact('revenue'));
    }

    public function maintenance()
    {
        $maintenance = [
            'total_issues' => \Modules\Hostel\Models\HostelIssue::count(),
            'resolved_issues' => \Modules\Hostel\Models\HostelIssue::where('status', 'resolved')->count(),
            'pending_issues' => \Modules\Hostel\Models\HostelIssue::where('status', 'pending')->count(),
            'by_category' => \Modules\Hostel\Models\HostelIssue::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get(),
        ];

        return view('hostel::statistics.maintenance', compact('maintenance'));
    }
}