<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Trip;
use Modules\Transport\Models\Vehicle;
use Modules\Transport\Models\Driver;
use Modules\Transport\Models\Fee;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_trips' => Trip::count(),
            'total_vehicles' => Vehicle::count(),
            'total_drivers' => Driver::count(),
            'total_students' => \Modules\Transport\Models\Student::count(),
            'completed_trips' => Trip::where('status', 'completed')->count(),
            'pending_fees' => Fee::where('status', 'pending')->count(),
        ];

        return view('transport::reports.index', compact('stats'));
    }

    public function trips()
    {
        $trips = Trip::with(['route', 'vehicle', 'driver'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('transport::reports.trips', compact('trips'));
    }

    public function vehicles()
    {
        $vehicles = Vehicle::with(['driver', 'trips'])
            ->withCount('trips')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('transport::reports.vehicles', compact('vehicles'));
    }

    public function drivers()
    {
        $drivers = Driver::with(['vehicle', 'trips'])
            ->withCount('trips')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('transport::reports.drivers', compact('drivers'));
    }

    public function fees()
    {
        $fees = Fee::with(['student', 'route'])
            ->orderBy('due_date', 'desc')
            ->paginate(50);

        return view('transport::reports.fees', compact('fees'));
    }

    public function maintenance()
    {
        $maintenance = \Modules\Transport\Models\Maintenance::with(['vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('transport::reports.maintenance', compact('maintenance'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'trips');
        
        // Basic export functionality
        return response()->json([
            'message' => "Exporting {$type} report...",
            'type' => $type
        ]);
    }
}
