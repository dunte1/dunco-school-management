<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Auth;
use Modules\ChatBot\Models\Conversation;
use Modules\ChatBot\Models\Message;

class ConversationService
{
    /**
     * Get or create a conversation for the user
     */
    public function getOrCreateConversation($userId = null)
    {
        if (!$userId && Auth::check()) {
            $userId = Auth::id();
        }

        $conversation = Conversation::where('user_id', $userId)
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $userId,
                'is_active' => true,
                'session_id' => uniqid(),
            ]);
        }

        return $conversation;
    }

    /**
     * Add a message to a conversation
     */
    public function addMessage(Conversation $conversation, string $content, string $role = 'user')
    {
        return Message::create([
            'conversation_id' => $conversation->id,
            'content' => $content,
            'role' => $role,
        ]);
    }

    /**
     * Get recent conversations for a user
     */
    public function getRecentConversations($userId = null, $limit = 10)
    {
        if (!$userId && Auth::check()) {
            $userId = Auth::id();
        }

        return Conversation::where('user_id', $userId)
            ->with(['messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * End a conversation
     */
    public function endConversation($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $conversation->update(['is_active' => false]);
        
        return $conversation;
    }

    /**
     * Get conversation by ID
     */
    public function getConversation($conversationId)
    {
        return Conversation::with('messages')->findOrFail($conversationId);
    }
} 