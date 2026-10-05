<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDeviceToken;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'platform' => 'nullable|string|in:android,ios,web',
            'device_id' => 'nullable|string',
            'app_version' => 'nullable|string',
            'firebase_token' => 'nullable|string',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Update or create device token
            $deviceToken = UserDeviceToken::updateOrCreate(
                ['token' => $request->string('token')],
                [
                    'user_id' => $user->id,
                    'platform' => $request->string('platform') ?: 'android',
                    'device_id' => $request->string('device_id'),
                    'app_version' => $request->string('app_version'),
                    'firebase_token' => $request->string('firebase_token'),
                    'last_seen_at' => now(),
                    'is_active' => true,
                ]
            );

            // If Firebase token provided, register with Firebase
            if ($request->string('firebase_token')) {
                $this->registerWithFirebase($request->string('firebase_token'), $user);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Device registered successfully',
                'device_id' => $deviceToken->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register device: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unregister(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $deviceToken = UserDeviceToken::where('token', $request->string('token'))
                ->where('user_id', $user->id)
                ->first();

            if ($deviceToken) {
                // Unregister from Firebase if token exists
                if ($deviceToken->firebase_token) {
                    $this->unregisterFromFirebase($deviceToken->firebase_token);
                }

                $deviceToken->update(['is_active' => false]);
                $deviceToken->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Device unregistered successfully'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Device token not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to unregister device: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'firebase_token' => 'nullable|string',
            'app_version' => 'nullable|string',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $deviceToken = UserDeviceToken::where('token', $request->string('token'))
                ->where('user_id', $user->id)
                ->first();

            if (!$deviceToken) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Device token not found'
                ], 404);
            }

            $updates = [
                'last_seen_at' => now(),
            ];

            if ($request->has('firebase_token')) {
                $updates['firebase_token'] = $request->string('firebase_token');
                // Register with Firebase if new token provided
                if ($request->string('firebase_token')) {
                    $this->registerWithFirebase($request->string('firebase_token'), $user);
                }
            }

            if ($request->has('app_version')) {
                $updates['app_version'] = $request->string('app_version');
            }

            $deviceToken->update($updates);

            return response()->json([
                'status' => 'success',
                'message' => 'Device updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update device: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $devices = UserDeviceToken::where('user_id', $user->id)
                ->where('is_active', true)
                ->get(['id', 'platform', 'device_id', 'app_version', 'last_seen_at']);

            return response()->json([
                'status' => 'success',
                'devices' => $devices
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to list devices: ' . $e->getMessage()
            ], 500);
        }
    }

    private function registerWithFirebase($firebaseToken, $user)
    {
        try {
            // This would integrate with Firebase Cloud Messaging
            // For now, we'll just log the registration
            \Log::info('Firebase registration', [
                'user_id' => $user->id,
                'firebase_token' => $firebaseToken,
                'timestamp' => now()
            ]);

            // In a real implementation, you would:
            // 1. Send the token to Firebase FCM
            // 2. Subscribe to relevant topics based on user role
            // 3. Handle any Firebase-specific errors

        } catch (\Exception $e) {
            \Log::error('Firebase registration failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function unregisterFromFirebase($firebaseToken)
    {
        try {
            // This would unregister from Firebase Cloud Messaging
            \Log::info('Firebase unregistration', [
                'firebase_token' => $firebaseToken,
                'timestamp' => now()
            ]);

            // In a real implementation, you would:
            // 1. Unsubscribe from all topics
            // 2. Remove the token from Firebase FCM

        } catch (\Exception $e) {
            \Log::error('Firebase unregistration failed', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
