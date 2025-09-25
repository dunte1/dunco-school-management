<?php

namespace Modules\Timetable\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    /**
     * List notifications for the authenticated user (paginated)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(20);
        return response()->json($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
} 