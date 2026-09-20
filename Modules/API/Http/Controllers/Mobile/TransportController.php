<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Transport\Models\Route;
use Modules\Transport\Models\Vehicle;
use Modules\Transport\Models\Driver;
use Modules\Transport\Models\Trip;
use Modules\Transport\Models\RouteStop;

class TransportController extends Controller
{
    public function getRoutes(Request $request): JsonResponse
    {
        try {
            $query = Route::with(['stops']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $routes = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Routes retrieved successfully',
                'data' => $routes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve routes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRoute($id): JsonResponse
    {
        try {
            $route = Route::with(['stops', 'trips'])->find($id);

            if (!$route) {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Route retrieved successfully',
                'data' => $route
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve route: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getVehicles(Request $request): JsonResponse
    {
        try {
            $query = Vehicle::with(['driver', 'route']);

            if ($request->filled('route_id')) {
                $query->where('route_id', $request->route_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('vehicle_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            }

            $vehicles = $query->orderBy('vehicle_number')->get();

            return response()->json([
                'success' => true,
                'message' => 'Vehicles retrieved successfully',
                'data' => $vehicles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vehicles: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getVehicle($id): JsonResponse
    {
        try {
            $vehicle = Vehicle::with(['driver', 'route', 'trips'])->find($id);

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Vehicle retrieved successfully',
                'data' => $vehicle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDrivers(Request $request): JsonResponse
    {
        try {
            $query = Driver::with(['vehicle']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $drivers = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Drivers retrieved successfully',
                'data' => $drivers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve drivers: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTrips(Request $request): JsonResponse
    {
        try {
            $query = Trip::with(['vehicle', 'driver', 'route']);

            if ($request->filled('route_id')) {
                $query->where('route_id', $request->route_id);
            }

            if ($request->filled('vehicle_id')) {
                $query->where('vehicle_id', $request->vehicle_id);
            }

            if ($request->filled('date')) {
                $query->where('trip_date', $request->date);
            }

            if ($request->filled('date_from')) {
                $query->where('trip_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('trip_date', '<=', $request->date_to);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $trips = $query->orderByDesc('trip_date')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Trips retrieved successfully',
                'data' => $trips
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve trips: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTrip($id): JsonResponse
    {
        try {
            $trip = Trip::with(['vehicle', 'driver', 'route', 'route.stops', 'students'])->find($id);

            if (!$trip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trip not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Trip retrieved successfully',
                'data' => $trip
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve trip: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMyRoute(): JsonResponse
    {
        try {
            $userId = Auth::id();

            // Find the student's assigned route through their transport allocation
            $trip = Trip::with(['vehicle', 'driver', 'route', 'route.stops'])
                ->whereHas('students', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$trip) {
                return response()->json([
                    'success' => true,
                    'message' => 'No active route found',
                    'data' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Route retrieved successfully',
                'data' => $trip
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve route: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRouteStops($routeId): JsonResponse
    {
        try {
            $stops = RouteStop::where('route_id', $routeId)
                ->orderBy('stop_order')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Route stops retrieved successfully',
                'data' => $stops
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve route stops: ' . $e->getMessage()
            ], 500);
        }
    }
}
