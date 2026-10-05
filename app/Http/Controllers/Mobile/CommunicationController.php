<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommunicationController extends Controller
{
    use ApiResponse;

    public function getNotifications(Request $request)
    {
        try {
            // Mock data for notifications
            $notifications = [
                ['id' => 1, 'title' => 'New Assignment', 'message' => 'Math homework due tomorrow', 'date' => '2024-01-15'],
                ['id' => 2, 'title' => 'Exam Reminder', 'message' => 'Science exam next week', 'date' => '2024-01-14'],
                ['id' => 3, 'title' => 'Parent Meeting', 'message' => 'Parent-teacher meeting scheduled', 'date' => '2024-01-13'],
            ];
            return $this->successResponse($notifications, 'Notifications retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting notifications: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve notifications.', 500);
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $user = $request->user();
            $conversationId = $request->query('conversation_id');
            
            $query = \Modules\Communication\Entities\Message::where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('recipient_id', $user->id);
            });

            // Filter by conversation if specified
            if ($conversationId) {
                $query->where(function($q) use ($conversationId, $user) {
                    $q->where('sender_id', $user->id)->where('recipient_id', $conversationId)
                      ->orWhere('sender_id', $conversationId)->where('recipient_id', $user->id);
                });
            }

            $messages = $query->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->get();

            $formattedMessages = $messages->map(function($message) use ($user) {
                $isSender = $message->sender_id == $user->id;
                $otherUser = $isSender ? $message->recipient : $message->sender;
                
                return [
                    'id' => $message->id,
                    'sender' => $otherUser ? $otherUser->name : 'Unknown',
                    'senderId' => $otherUser ? $otherUser->id : null,
                    'message' => $message->content,
                    'date' => $message->created_at->format('Y-m-d'),
                    'time' => $message->created_at->format('H:i'),
                    'isRead' => $message->is_read,
                    'isSender' => $isSender,
                    'type' => $message->type ?? 'text',
                ];
            });

            return $this->successResponse($formattedMessages, 'Messages retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting messages: " . $e->getMessage());
            // Fallback to mock data
            $messages = [
                ['id' => 1, 'sender' => 'Mr. John Kamau', 'senderId' => 2, 'message' => 'Good morning class! Remember to submit your math homework today.', 'date' => '2024-01-15', 'time' => '08:30', 'isRead' => true, 'isSender' => false, 'type' => 'text'],
                ['id' => 2, 'sender' => 'You', 'senderId' => 1, 'message' => 'Can you help me with the physics problem?', 'date' => '2024-01-15', 'time' => '14:20', 'isRead' => true, 'isSender' => true, 'type' => 'text'],
                ['id' => 3, 'sender' => 'Ms. Sarah Wanjiku', 'senderId' => 3, 'message' => 'Thank you for the update. I will attend the parent meeting.', 'date' => '2024-01-14', 'time' => '16:45', 'isRead' => false, 'isSender' => false, 'type' => 'text'],
            ];
            return $this->successResponse($messages, 'Messages retrieved successfully (demo data)');
        }
    }

    public function getAnnouncements(Request $request)
    {
        try {
            // Mock data for announcements
            $announcements = [
                ['id' => 1, 'title' => 'School Holiday', 'message' => 'School will be closed on Monday', 'date' => '2024-01-15'],
                ['id' => 2, 'title' => 'Sports Day', 'message' => 'Annual sports day next Friday', 'date' => '2024-01-14'],
                ['id' => 3, 'title' => 'Library Hours', 'message' => 'Library will be open until 6 PM', 'date' => '2024-01-13'],
            ];
            return $this->successResponse($announcements, 'Announcements retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting announcements: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve announcements.', 500);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $user = $request->user();
            $recipientId = $request->input('recipient_id');
            $message = $request->input('message');
            $type = $request->input('type', 'text');

            if (!$recipientId || !$message) {
                return $this->errorResponse('Recipient ID and message are required.', 400);
            }

            // Create the message
            $newMessage = \Modules\Communication\Entities\Message::create([
                'sender_id' => $user->id,
                'recipient_id' => $recipientId,
                'message' => $message,
                'type' => $type,
                'school_id' => $user->school_id,
            ]);

            // Load relationships
            $newMessage->load(['sender', 'recipient']);

            // Format response
            $formattedMessage = [
                'id' => $newMessage->id,
                'sender_id' => $newMessage->sender_id,
                'recipient_id' => $newMessage->recipient_id,
                'message' => $newMessage->message,
                'type' => $newMessage->type,
                'timestamp' => $newMessage->created_at->toISOString(),
                'sender_name' => $newMessage->sender->name,
                'sender_avatar' => $newMessage->sender->profile_photo_url ?? null,
            ];

            // Trigger real-time event
            broadcast(new \Modules\Communication\Events\MessageSent($newMessage))->toOthers();

            return $this->successResponse($formattedMessage, 'Message sent successfully');
        } catch (\Exception $e) {
            Log::error("Error sending message: " . $e->getMessage());
            return $this->errorResponse('Failed to send message.', 500);
        }
    }

    public function markMessageAsRead(Request $request, $messageId)
    {
        try {
            $user = $request->user();
            
            $message = \Modules\Communication\Entities\Message::where('id', $messageId)
                ->where('recipient_id', $user->id)
                ->first();

            if (!$message) {
                return $this->errorResponse('Message not found.', 404);
            }

            $message->update(['read_at' => now()]);

            return $this->successResponse(null, 'Message marked as read');
        } catch (\Exception $e) {
            Log::error("Error marking message as read: " . $e->getMessage());
            return $this->errorResponse('Failed to mark message as read.', 500);
        }
    }
}
