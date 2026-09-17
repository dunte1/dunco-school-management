<?php

namespace Modules\API\Http\Controllers\Mobile;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    public function getNotifications(): JsonResponse
    {
        $notifications = auth()->user()->notifications()->orderByDesc('created_at')->paginate(25);
        return response()->json(['success' => true, 'data' => $notifications]);
    }

    public function markAsRead($id): JsonResponse
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if (!$notification) return response()->json(['message' => 'Notification not found'], 404);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
