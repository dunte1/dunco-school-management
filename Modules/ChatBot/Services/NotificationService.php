<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Modules\ChatBot\Notifications\ChatBotAlert;
use Modules\ChatBot\Models\Conversation;
use Modules\ChatBot\Models\ChatBotNotification;

class NotificationService
{
    /**
     * Send notification to admin about chatbot issues
     *
     * @param string $message
     * @param string $type
     * @param array $data
     * @return void
     */
    public function sendAdminNotification($message, $type = 'info', $data = [])
    {
        // Get admin users
        $admins = $this->getAdminUsers();
        
        if ($admins->isEmpty()) {
            return;
        }
        
        // Store notification in database for each admin
        foreach ($admins as $admin) {
            ChatBotNotification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'message' => $message,
                'data' => $data,
            ]);
        }
        
        // Send notification
        Notification::send($admins, new ChatBotAlert($message, $type, $data));
    }
    
    /**
     * Send email notification to admin
     *
     * @param string $subject
     * @param string $message
     * @param array $data
     * @return void
     */
    public function sendAdminEmail($subject, $message, $data = [])
    {
        // Get admin users with email
        $admins = $this->getAdminUsersWithEmail();
        
        if ($admins->isEmpty()) {
            return;
        }
        
        // Send email
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new \Modules\ChatBot\Mail\ChatBotNotification($subject, $message, $data));
        }
    }
    
    /**
     * Send notification to user about conversation
     *
     * @param int $userId
     * @param string $message
     * @param int $conversationId
     * @return void
     */
    public function sendUserNotification($userId, $message, $conversationId = null)
    {
        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            return;
        }
        
        // Send notification
        $user->notify(new ChatBotAlert($message, 'info', [
            'conversation_id' => $conversationId,
        ]));
    }
    
    /**
     * Send alert for high-priority issues
     *
     * @param string $message
     * @param array $data
     * @return void
     */
    public function sendAlert($message, $data = [])
    {
        // Send to admins
        $this->sendAdminNotification($message, 'alert', $data);
        
        // Send email
        $this->sendAdminEmail('ChatBot Alert: ' . $message, $message, $data);
        
        // Log the alert
        \Log::alert('ChatBot Alert: ' . $message, $data);
    }
    
    /**
     * Send notification for rate limit exceeded
     *
     * @param int $userId
     * @param string $ipAddress
     * @return void
     */
    public function sendRateLimitNotification($userId = null, $ipAddress = null)
    {
        $message = 'Rate limit exceeded';
        $data = [
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'timestamp' => now(),
        ];
        
        $this->sendAlert($message, $data);
    }
    
    /**
     * Send notification for API errors
     *
     * @param string $error
     * @param array $context
     * @return void
     */
    public function sendApiErrorNotification($error, $context = [])
    {
        $message = 'API Error: ' . $error;
        $data = array_merge([
            'error' => $error,
            'timestamp' => now(),
        ], $context);
        
        $this->sendAlert($message, $data);
    }
    
    /**
     * Send notification for conversation flagged as inappropriate
     *
     * @param int $conversationId
     * @param string $reason
     * @return void
     */
    public function sendInappropriateContentNotification($conversationId, $reason)
    {
        $conversation = Conversation::find($conversationId);
        
        if (!$conversation) {
            return;
        }
        
        $message = 'Inappropriate content detected in conversation';
        $data = [
            'conversation_id' => $conversationId,
            'user_id' => $conversation->user_id,
            'reason' => $reason,
            'timestamp' => now(),
        ];
        
        $this->sendAlert($message, $data);
    }
    
    /**
     * Send notification for high usage
     *
     * @param int $usageCount
     * @param string $period
     * @return void
     */
    public function sendHighUsageNotification($usageCount, $period = 'day')
    {
        $message = "High chatbot usage detected: {$usageCount} interactions in the last {$period}";
        $data = [
            'usage_count' => $usageCount,
            'period' => $period,
            'timestamp' => now(),
        ];
        
        $this->sendAdminNotification($message, 'warning', $data);
    }
    
    /**
     * Get admin users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAdminUsers()
    {
        return \App\Models\User::where('role', 'admin')
            ->whereHas('permissions', function ($query) {
                $query->where('name', 'chatbot.admin');
            })
            ->get();
    }
    
    /**
     * Get admin users with email
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAdminUsersWithEmail()
    {
        return \App\Models\User::where('role', 'admin')
            ->whereNotNull('email')
            ->whereHas('permissions', function ($query) {
                $query->where('name', 'chatbot.admin');
            })
            ->get();
    }
    
    /**
     * Send real-time notification (if using Pusher or similar)
     *
     * @param string $channel
     * @param string $event
     * @param array $data
     * @return void
     */
    public function sendRealTimeNotification($channel, $event, $data = [])
    {
        // Check if broadcasting is configured
        if (config('broadcasting.default') && config('broadcasting.default') !== 'null') {
            event(new \Modules\ChatBot\Events\ChatBotNotification($channel, $event, $data));
        }
    }
    
    /**
     * Send notification to all users with chatbot access
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    public function sendBroadcastNotification($message, $type = 'info')
    {
        // Get all users with chatbot access
        $users = \App\Models\User::whereHas('permissions', function ($query) {
            $query->where('name', 'chatbot.view')
                  ->orWhere('name', 'chatbot.admin');
        })->get();
        
        if ($users->isEmpty()) {
            return;
        }
        
        // Send notification
        Notification::send($users, new ChatBotAlert($message, $type));
    }
}