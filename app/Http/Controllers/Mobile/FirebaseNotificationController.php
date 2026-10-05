<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FirebaseNotificationController extends Controller
{
    use ApiResponse;

    // Firebase configuration from your .env
    private $firebaseConfig = [
        'project_id' => 'school-management-system-4577d',
        'private_key_id' => '47d91bb6a25a5aaf7cf564e4e5295902b0d6f15d',
        'client_email' => 'firebase-adminsdk-fbsvc@school-management-system-4577d.iam.gserviceaccount.com',
        'client_id' => '109777181060210253074',
    ];

    public function updateFCMToken(Request $request)
    {
        try {
            $user = $request->user();
            $fcmToken = $request->input('fcm_token');

            if (!$fcmToken) {
                return $this->errorResponse('FCM token is required.', 400);
            }

            // Update user's FCM token
            $user->update(['fcm_token' => $fcmToken]);

            return $this->successResponse(null, 'FCM token updated successfully');
        } catch (\Exception $e) {
            Log::error("Error updating FCM token: " . $e->getMessage());
            return $this->errorResponse('Failed to update FCM token.', 500);
        }
    }

    public function sendNotification(Request $request)
    {
        try {
            $user = $request->user();
            $targetUserId = $request->input('user_id');
            $notification = $request->input('notification');

            if (!$targetUserId || !$notification) {
                return $this->errorResponse('User ID and notification are required.', 400);
            }

            // Get target user's FCM token
            $targetUser = \App\Models\User::find($targetUserId);
            if (!$targetUser || !$targetUser->fcm_token) {
                return $this->errorResponse('Target user not found or has no FCM token.', 404);
            }

            // Send notification
            $this->sendFirebaseNotification($targetUser->fcm_token, $notification);

            return $this->successResponse(null, 'Notification sent successfully');
        } catch (\Exception $e) {
            Log::error("Error sending notification: " . $e->getMessage());
            return $this->errorResponse('Failed to send notification.', 500);
        }
    }

    public function sendNotificationToRole(Request $request)
    {
        try {
            $user = $request->user();
            $role = $request->input('role');
            $notification = $request->input('notification');

            if (!$role || !$notification) {
                return $this->errorResponse('Role and notification are required.', 400);
            }

            // Get all users with the specified role and FCM tokens
            $users = \App\Models\User::where('type', $role)
                ->whereNotNull('fcm_token')
                ->get();

            $sentCount = 0;
            foreach ($users as $targetUser) {
                try {
                    $this->sendFirebaseNotification($targetUser->fcm_token, $notification);
                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to send notification to user {$targetUser->id}: " . $e->getMessage());
                }
            }

            return $this->successResponse([
                'sent_count' => $sentCount,
                'total_users' => $users->count()
            ], 'Notifications sent successfully');
        } catch (\Exception $e) {
            Log::error("Error sending role notification: " . $e->getMessage());
            return $this->errorResponse('Failed to send role notification.', 500);
        }
    }

    public function sendNotificationToSchool(Request $request)
    {
        try {
            $user = $request->user();
            $notification = $request->input('notification');

            if (!$notification) {
                return $this->errorResponse('Notification is required.', 400);
            }

            // Get all users in the same school with FCM tokens
            $users = \App\Models\User::where('school_id', $user->school_id)
                ->whereNotNull('fcm_token')
                ->get();

            $sentCount = 0;
            foreach ($users as $targetUser) {
                try {
                    $this->sendFirebaseNotification($targetUser->fcm_token, $notification);
                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to send notification to user {$targetUser->id}: " . $e->getMessage());
                }
            }

            return $this->successResponse([
                'sent_count' => $sentCount,
                'total_users' => $users->count()
            ], 'School notifications sent successfully');
        } catch (\Exception $e) {
            Log::error("Error sending school notification: " . $e->getMessage());
            return $this->errorResponse('Failed to send school notification.', 500);
        }
    }

    private function sendFirebaseNotification($fcmToken, $notification)
    {
        try {
            // Get Firebase access token
            $accessToken = $this->getFirebaseAccessToken();

            // Prepare notification payload
            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $notification['title'] ?? 'Dunco School',
                        'body' => $notification['body'] ?? 'You have a new notification',
                    ],
                    'data' => [
                        'type' => $notification['type'] ?? 'general',
                        'data' => json_encode($notification['data'] ?? []),
                    ],
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'dunco_school_notifications',
                        ],
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                                'badge' => 1,
                            ],
                        ],
                    ],
                ],
            ];

            // Send to Firebase FCM
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://fcm.googleapis.com/v1/projects/' . $this->firebaseConfig['project_id'] . '/messages:send', $payload);

            if ($response->successful()) {
                Log::info('Firebase notification sent successfully', [
                    'fcm_token' => substr($fcmToken, 0, 20) . '...',
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Firebase notification failed', [
                    'fcm_token' => substr($fcmToken, 0, 20) . '...',
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Firebase notification failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Firebase notification error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getFirebaseAccessToken()
    {
        try {
            // In a real implementation, you would use the Firebase Admin SDK
            // For now, we'll use a mock token
            $mockToken = 'mock_firebase_token_' . time();
            
            // Store in cache for 1 hour
            \Cache::put('firebase_access_token', $mockToken, 3600);
            
            return $mockToken;
        } catch (\Exception $e) {
            Log::error('Failed to get Firebase access token: ' . $e->getMessage());
            throw $e;
        }
    }
}
