<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Trip;
use Modules\Transport\Models\Vehicle;
use Modules\Transport\Models\Driver;
use Modules\Transport\Models\Fee;
use Modules\Transport\Models\Student;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_trips' => Trip::count(),
            'total_vehicles' => Vehicle::count(),
            'total_drivers' => Driver::count(),
            'total_students' => Student::count(),
            'completed_trips' => Trip::where('status', 'completed')->count(),
            'active_vehicles' => Vehicle::where('status', 'active')->count(),
            'pending_fees' => Fee::where('status', 'pending')->count(),
            'total_revenue' => Fee::where('status', 'paid')->sum('amount'),
        ];

        return view('transport::statistics.index', compact('stats'));
    }

    public function trips()
    {
        $tripStats = [
            'by_status' => Trip::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'by_month' => Trip::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'by_route' => Trip::with('route')
                ->selectRaw('route_id, COUNT(*) as count')
                ->groupBy('route_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('transport::statistics.trips', compact('tripStats'));
    }

    public function vehicles()
    {
        $vehicleStats = [
            'by_status' => Vehicle::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'by_type' => Vehicle::selectRaw('vehicle_type, COUNT(*) as count')
                ->groupBy('vehicle_type')
                ->get(),
            'maintenance_needed' => Vehicle::where('last_maintenance', '<=', now()->subMonths(6))->count(),
            'fuel_efficiency' => Vehicle::selectRaw('vehicle_number, AVG(fuel_consumption) as avg_consumption')
                ->groupBy('vehicle_number')
                ->orderBy('avg_consumption')
                ->limit(10)
                ->get(),
        ];

        return view('transport::statistics.vehicles', compact('vehicleStats'));
    }

    public function revenue()
    {
        $revenueStats = [
            'total_collected' => Fee::where('status', 'paid')->sum('amount'),
            'total_pending' => Fee::where('status', 'pending')->sum('amount'),
            'total_overdue' => Fee::where('status', 'overdue')->sum('amount'),
            'by_month' => Fee::selectRaw('MONTH(due_date) as month, SUM(amount) as total')
                ->whereYear('due_date', date('Y'))
                ->where('status', 'paid')
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'by_type' => Fee::selectRaw('fee_type, SUM(amount) as total')
                ->where('status', 'paid')
                ->groupBy('fee_type')
                ->get(),
        ];

        return view('transport::statistics.revenue', compact('revenueStats'));
    }

    public function maintenance()
    {
        $maintenanceStats = [
            'total_issues' => \Modules\Transport\Models\Maintenance::count(),
            'resolved_issues' => \Modules\Transport\Models\Maintenance::where('status', 'resolved')->count(),
            'pending_issues' => \Modules\Transport\Models\Maintenance::where('status', 'pending')->count(),
            'by_category' => \Modules\Transport\Models\Maintenance::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get(),
            'by_vehicle' => \Modules\Transport\Models\Maintenance::with('vehicle')
                ->selectRaw('vehicle_id, COUNT(*) as count')
                ->groupBy('vehicle_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('transport::statistics.maintenance', compact('maintenanceStats'));
    }
}
