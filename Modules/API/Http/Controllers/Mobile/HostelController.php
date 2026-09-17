<?php

namespace Modules\API\Http\Controllers\Mobile;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class HostelController extends Controller
{
    public function getHostels(): JsonResponse
    {
        $hostels = \Modules\Hostel\Models\Hostel::orderBy('name')->get();
        return response()->json(['success' => true, 'data' => $hostels]);
    }

    public function getRooms(): JsonResponse
    {
        $rooms = \Modules\Hostel\Models\Room::with('hostel')->orderBy('room_number')->get();
        return response()->json(['success' => true, 'data' => $rooms]);
    }

    public function getMyAllocation(): JsonResponse
    {
        $allocation = \Modules\Hostel\Models\RoomAllocation::where('student_id', auth()->id())
            ->with(['room', 'bed'])
            ->latest()
            ->first();
        return response()->json(['success' => true, 'data' => $allocation]);
    }

    public function getAnnouncements(): JsonResponse
    {
        $announcements = \Modules\Hostel\Models\HostelAnnouncement::where('is_active', true)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        return response()->json(['success' => true, 'data' => $announcements]);
    }
}
