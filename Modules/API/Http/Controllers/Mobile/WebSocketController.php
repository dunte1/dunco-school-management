<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class WebSocketController extends Controller
{
    protected $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );
    }

    /**
     * Connect to WebSocket and subscribe to user channels
     */
    public function connect(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Generate authentication token for private channels
            $authToken = $this->pusher->socket_auth(
                $request->input('channel_name'),
                $request->input('socket_id')
            );

            // Subscribe user to relevant channels
            $this->subscribeToUserChannels($user);

            Log::info("WebSocket connection established for user: {$user->name}");

            return response()->json([
                'success' => true,
                'auth' => $authToken,
                'channels' => $this->getUserChannels($user),
                'message' => 'WebSocket connection established'
            ]);

        } catch (\Exception $e) {
            Log::error("WebSocket connection failed: " . $e->getMessage());
            return response()->json(['error' => 'Connection failed'], 500);
        }
    }

    /**
     * Subscribe user to relevant channels based on their role
     */
    private function subscribeToUserChannels($user)
    {
        $channels = $this->getUserChannels($user);

        foreach ($channels as $channel) {
            try {
                $this->pusher->trigger($channel, 'user.subscribed', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'timestamp' => now()->toISOString()
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to subscribe to channel {$channel}: " . $e->getMessage());
            }
        }
    }

    /**
     * Get list of channels user should subscribe to
     */
    private function getUserChannels($user)
    {
        $channels = [
            "private-user.{$user->id}"
        ];

        // Add role-based channels
        if ($user->hasRole('student')) {
            $student = $user->student;
            if ($student && $student->class_id) {
                $channels[] = "private-class.{$student->class_id}";
            }
        }

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                $channels[] = "private-teacher.{$teacher->id}";
                
                // Add channels for classes the teacher teaches
                $classes = $teacher->classes ?? collect();
                foreach ($classes as $class) {
                    $channels[] = "private-class.{$class->id}";
                }
            }
        }

        if ($user->hasRole('admin')) {
            $channels[] = "private-admin";
            $channels[] = "private-finance.admin";
        }

        if ($user->hasRole('parent')) {
            $parent = $user->parent;
            if ($parent) {
                $children = $parent->children ?? collect();
                foreach ($children as $child) {
                    if ($child->class_id) {
                        $channels[] = "private-class.{$child->class_id}";
                    }
                }
            }
        }

        return array_unique($channels);
    }

    /**
     * Send test message to user
     */
    public function sendTestMessage(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $message = $request->input('message', 'Test message from server');
            $channel = "private-user.{$user->id}";

            $this->pusher->trigger($channel, 'test.message', [
                'message' => $message,
                'timestamp' => now()->toISOString(),
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test message sent'
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to send test message: " . $e->getMessage());
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Get user's current channels
     */
    public function getChannels(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json([
                'success' => true,
                'channels' => $this->getUserChannels($user)
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get channels: " . $e->getMessage());
            return response()->json(['error' => 'Failed to get channels'], 500);
        }
    }
}
