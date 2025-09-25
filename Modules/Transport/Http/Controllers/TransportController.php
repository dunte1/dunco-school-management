<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Models\Vehicle;
use Modules\Transport\Models\Driver;
use Modules\Transport\Models\Route;
use Modules\Transport\Models\Trip;

class TransportController extends Controller
{
    public function index()
    {
        // Get transport statistics
        $stats = $this->getTransportStats();
        
        // Get recent trips
        $recentTrips = Trip::with(['vehicle', 'driver', 'route'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get active vehicles
        $activeVehicles = Vehicle::with('driver')
            ->where('status', 'active')
            ->limit(5)
            ->get();
            
        // Get upcoming trips
        $upcomingTrips = Trip::with(['vehicle', 'driver', 'route'])
            ->where('trip_date', '>=', now()->toDateString())
            ->where('status', 'scheduled')
            ->orderBy('trip_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        return view('transport::index', compact('stats', 'recentTrips', 'activeVehicles', 'upcomingTrips'));
    }

    private function getTransportStats()
    {
        return [
            'total_vehicles' => Vehicle::count(),
            'active_vehicles' => Vehicle::where('status', 'active')->count(),
            'maintenance_vehicles' => Vehicle::where('status', 'maintenance')->count(),
            'total_drivers' => Driver::count(),
            'active_drivers' => Driver::where('status', 'active')->count(),
            'total_routes' => Route::count(),
            'active_routes' => Route::where('status', 'active')->count(),
            'total_trips' => Trip::count(),
            'today_trips' => Trip::whereDate('trip_date', today())->count(),
            'scheduled_trips' => Trip::where('status', 'scheduled')->count(),
            'in_progress_trips' => Trip::where('status', 'in_progress')->count(),
            'completed_trips' => Trip::where('status', 'completed')->count(),
            'total_passengers' => Trip::sum('passenger_count'),
            'total_distance' => Trip::sum('distance_covered'),
            'total_fuel' => Trip::sum('fuel_consumed'),
        ];
    }

    public function reports()
    {
        $monthlyTrips = Trip::selectRaw('MONTH(trip_date) as month, COUNT(*) as count')
            ->whereYear('trip_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $vehicleUtilization = Vehicle::withCount(['trips' => function($query) {
            $query->whereMonth('trip_date', date('m'));
        }])
        ->orderBy('trips_count', 'desc')
        ->limit(10)
        ->get();

        $driverPerformance = Driver::withCount(['trips' => function($query) {
            $query->whereMonth('trip_date', date('m'));
        }])
        ->orderBy('trips_count', 'desc')
        ->limit(10)
        ->get();

        return view('transport::reports', compact('monthlyTrips', 'vehicleUtilization', 'driverPerformance'));
    }
}
