<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\Bed;
use Modules\Hostel\Models\RoomAllocation;
use Modules\Hostel\Models\HostelAnnouncement;
use Modules\Hostel\Models\HostelIssue;
use Modules\Hostel\Models\HostelFee;

class HostelController extends Controller
{
    public function getHostels(Request $request): JsonResponse
    {
        try {
            $query = Hostel::with(['rooms']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }

            $hostels = $query->orderBy('name')->get();

            // Add capacity info
            $hostels->each(function ($hostel) {
                $hostel->total_rooms = $hostel->rooms->count();
                $hostel->occupied_rooms = RoomAllocation::whereHas('room', function ($q) use ($hostel) {
                    $q->where('hostel_id', $hostel->id);
                })->where('status', 'active')->count();
                $hostel->available_rooms = $hostel->total_rooms - $hostel->occupied_rooms;
            });

            return response()->json([
                'success' => true,
                'message' => 'Hostels retrieved successfully',
                'data' => $hostels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve hostels: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getHostel($id): JsonResponse
    {
        try {
            $hostel = Hostel::with(['rooms.beds', 'floor'])->find($id);

            if (!$hostel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hostel not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Hostel retrieved successfully',
                'data' => $hostel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve hostel: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRooms(Request $request): JsonResponse
    {
        try {
            $query = Room::with(['hostel', 'beds']);

            if ($request->filled('hostel_id')) {
                $query->where('hostel_id', $request->hostel_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('room_type')) {
                $query->where('room_type', $request->room_type);
            }

            $rooms = $query->orderBy('room_number')->get();

            // Add allocation status
            $rooms->each(function ($room) {
                $room->total_beds = $room->beds->count();
                $room->occupied_beds = RoomAllocation::where('room_id', $room->id)
                    ->where('status', 'active')
                    ->count();
                $room->available_beds = $room->total_beds - $room->occupied_beds;
            });

            return response()->json([
                'success' => true,
                'message' => 'Rooms retrieved successfully',
                'data' => $rooms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve rooms: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRoom($id): JsonResponse
    {
        try {
            $room = Room::with(['hostel', 'beds', 'allocations.student'])->find($id);

            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Room retrieved successfully',
                'data' => $room
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMyAllocation(): JsonResponse
    {
        try {
            $userId = Auth::id();

            $allocation = RoomAllocation::with(['room', 'room.hostel', 'bed'])
                ->where('student_id', $userId)
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$allocation) {
                return response()->json([
                    'success' => true,
                    'message' => 'No active allocation found',
                    'data' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Allocation retrieved successfully',
                'data' => $allocation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve allocation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAnnouncements(Request $request): JsonResponse
    {
        try {
            $query = HostelAnnouncement::where('is_active', true);

            if ($request->filled('hostel_id')) {
                $query->where('hostel_id', $request->hostel_id);
            }

            $announcements = $query->orderByDesc('created_at')
                ->limit($request->get('limit', 10))
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Announcements retrieved successfully',
                'data' => $announcements
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve announcements: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getIssues(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();

            $query = HostelIssue::with(['room', 'reportedBy']);

            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            } else {
                // Students can only see their own issues
                $query->where('student_id', $userId);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            $issues = $query->orderByDesc('created_at')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Issues retrieved successfully',
                'data' => $issues
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve issues: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reportIssue(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = Auth::id();

            $issue = HostelIssue::create([
                'room_id' => $request->room_id,
                'student_id' => $userId,
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'category' => $request->category,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Issue reported successfully',
                'data' => $issue
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to report issue: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getHostelFees(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();

            $query = HostelFee::with(['hostel']);

            if ($request->filled('hostel_id')) {
                $query->where('hostel_id', $request->hostel_id);
            }

            $fees = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Hostel fees retrieved successfully',
                'data' => $fees
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve hostel fees: ' . $e->getMessage()
            ], 500);
        }
    }
}
