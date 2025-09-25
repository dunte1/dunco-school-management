<?php

namespace Modules\API\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\RoomAllocation;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\AcademicClass;
use Modules\HR\Models\Staff;


class APIController extends Controller
{
    /**
     * Display a listing of the APIs.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'DUNCO School Management System API',
            'version' => '1.0.0',
            'endpoints' => [
                'GET /apis' => 'API Information',
                'GET /apis/users' => 'List all users',
                'GET /apis/hostels' => 'List all hostels',
                'GET /apis/students' => 'List all students',
                'GET /apis/staff' => 'List all staff',
                'GET /apis/classes' => 'List all academic classes',
                'GET /apis/room-allocations' => 'List room allocations',
                'GET /apis/stats' => 'System statistics',
            ],
            'status' => 'active'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'API endpoint created successfully',
            'data' => $request->all()
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        return response()->json([
            'message' => 'API endpoint details',
            'id' => $id,
            'data' => null
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'API endpoint updated successfully',
            'id' => $id,
            'data' => $request->all()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'message' => 'API endpoint deleted successfully',
            'id' => $id
        ]);
    }

    /**
     * Get all users
     */
    public function users(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'is_active', 'created_at')
            ->paginate(20);

        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users
        ]);
    }

    /**
     * Get all hostels
     */
    public function hostels(): JsonResponse
    {
        $hostels = Hostel::with(['rooms', 'wardens'])
            ->paginate(20);

        return response()->json([
            'message' => 'Hostels retrieved successfully',
            'data' => $hostels
        ]);
    }

    /**
     * Get all students
     */
    public function students(): JsonResponse
    {
        $students = Student::with(['user', 'academicClass'])
            ->paginate(20);

        return response()->json([
            'message' => 'Students retrieved successfully',
            'data' => $students
        ]);
    }

    /**
     * Get all staff
     */
    public function staff(): JsonResponse
    {
        $staff = Staff::with(['user', 'department'])
            ->paginate(20);

        return response()->json([
            'message' => 'Staff retrieved successfully',
            'data' => $staff
        ]);
    }

    /**
     * Get all academic classes
     */
    public function classes(): JsonResponse
    {
        $classes = AcademicClass::paginate(20);

        return response()->json([
            'message' => 'Academic classes retrieved successfully',
            'data' => $classes
        ]);
    }

    /**
     * Get room allocations
     */
    public function roomAllocations(): JsonResponse
    {
        $allocations = RoomAllocation::with(['student', 'room', 'hostel'])
            ->paginate(20);

        return response()->json([
            'message' => 'Room allocations retrieved successfully',
            'data' => $allocations
        ]);
    }

    /**
     * System statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_hostels' => Hostel::count(),
            'total_rooms' => Room::count(),
            'total_students' => Student::count(),
            'total_staff' => Staff::count(),
            'total_classes' => AcademicClass::count(),
            'total_languages' => Language::count(),
            'active_allocations' => RoomAllocation::where('status', 'active')->count(),
        ];

        return response()->json([
            'message' => 'System statistics retrieved successfully',
            'data' => $stats
        ]);
    }
} 