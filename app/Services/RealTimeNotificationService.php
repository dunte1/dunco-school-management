<?php

namespace App\Services;

use App\Events\RealTimeNotification;
use App\Events\CommunicationNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class RealTimeNotificationService
{
    /**
     * Send a real-time notification to a specific user
     */
    public function sendToUser($userId, $notification)
    {
        try {
            $event = new RealTimeNotification($notification, $userId);
            broadcast($event);
            
            Log::info('Real-time notification sent to user', [
                'user_id' => $userId,
                'notification' => $notification
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send real-time notification', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send a real-time notification to multiple users
     */
    public function sendToUsers($userIds, $notification)
    {
        $results = [];
        
        foreach ($userIds as $userId) {
            $results[$userId] = $this->sendToUser($userId, $notification);
        }
        
        return $results;
    }

    /**
     * Send a communication notification
     */
    public function sendCommunicationNotification($message, $recipients, $type = 'message')
    {
        try {
            $event = new CommunicationNotification($message, $recipients, $type);
            broadcast($event);
            
            Log::info('Communication notification sent', [
                'type' => $type,
                'recipients_count' => count($recipients),
                'message_id' => $message['id'] ?? null
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send communication notification', [
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send notification to users by role
     */
    public function sendToRole($role, $notification)
    {
        try {
            $users = \App\Models\User::where('role', $role)->pluck('id')->toArray();
            return $this->sendToUsers($users, $notification);
        } catch (\Exception $e) {
            Log::error('Failed to send notification to role', [
                'role' => $role,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send notification to all online users
     */
    public function sendToAll($notification)
    {
        try {
            $event = new RealTimeNotification($notification);
            broadcast($event);
            
            Log::info('Broadcast notification sent to all users', [
                'notification' => $notification
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to broadcast notification', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send urgent notification with high priority
     */
    public function sendUrgent($userId, $title, $message, $url = null)
    {
        $notification = [
            'type' => 'urgent',
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'icon' => 'fas fa-exclamation-triangle',
            'priority' => 'high'
        ];

        return $this->sendToUser($userId, $notification);
    }

    /**
     * Send system notification
     */
    public function sendSystem($userId, $title, $message, $url = null)
    {
        $notification = [
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'icon' => 'fas fa-cog',
            'priority' => 'normal'
        ];

        return $this->sendToUser($userId, $notification);
    }
}
