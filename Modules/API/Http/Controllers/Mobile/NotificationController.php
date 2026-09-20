<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $query = $user->notifications();

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->has('unread_only') && $request->unread_only) {
                $query->unread();
            }

            $notifications = $query->orderByDesc('created_at')
                ->paginate($request->get('per_page', 25));

            return response()->json([
                'success' => true,
                'message' => 'Notifications retrieved successfully',
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUnreadCount(): JsonResponse
    {
        try {
            $user = Auth::user();
            $count = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'message' => 'Unread count retrieved successfully',
                'data' => ['count' => $count]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve unread count: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->where('id', $id)->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAllAsRead(): JsonResponse
    {
        try {
            $user = Auth::user();
            $user->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteNotification($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->where('id', $id)->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAllRead(): JsonResponse
    {
        try {
            $user = Auth::user();
            $user->notifications()->where('read_at', '!=', null)->delete();

            return response()->json([
                'success' => true,
                'message' => 'All read notifications deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete read notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDeviceToken(Request $request): JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'device_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $user->device_token = $request->device_token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Device token updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update device token: ' . $e->getMessage()
            ], 500);
        }
    }
}
