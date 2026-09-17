<?php

namespace Modules\API\Http\Controllers\Mobile;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class TransportController extends Controller
{
    public function getRoutes(): JsonResponse
    {
        $routes = \Modules\Transport\Models\Route::orderBy('name')->get();
        return response()->json(['success' => true, 'data' => $routes]);
    }

    public function getVehicles(): JsonResponse
    {
        $vehicles = \Modules\Transport\Models\Vehicle::with('driver')->orderBy('vehicle_number')->get();
        return response()->json(['success' => true, 'data' => $vehicles]);
    }

    public function getTrips(): JsonResponse
    {
        $trips = \Modules\Transport\Models\Trip::with(['vehicle', 'driver', 'route'])
            ->orderByDesc('trip_date')
            ->paginate(25);
        return response()->json(['success' => true, 'data' => $trips]);
    }

    public function getTrip($id): JsonResponse
    {
        $trip = \Modules\Transport\Models\Trip::with(['vehicle', 'driver', 'route'])->find($id);
        if (!$trip) return response()->json(['message' => 'Trip not found'], 404);
        return response()->json(['success' => true, 'data' => $trip]);
    }
}
