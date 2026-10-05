<?php

namespace Modules\ChatBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatBotConversation extends Model
{
    protected $table = 'chatbot_conversations';
    
    protected $fillable = [
        'user_id',
        'title',
        'context',
        'status',
        'last_message_at',
        'message_count',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_message_at' => 'datetime'
    ];

    /**
     * Get the user that owns the conversation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the messages for the conversation
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatBotMessage::class, 'conversation_id');
    }

    /**
     * Scope for active conversations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for ended conversations
     */
    public function scopeEnded($query)
    {
        return $query->where('status', 'ended');
    }

    /**
     * Scope for archived conversations
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Get the latest message for the conversation
     */
    public function latestMessage()
    {
        return $this->hasOne(ChatBotMessage::class)->latest();
    }

    /**
     * Increment message count
     */
    public function incrementMessageCount()
    {
        $this->increment('message_count');
        $this->update(['last_message_at' => now()]);
    }

    /**
     * Get conversation summary
     */
    public function getSummaryAttribute()
    {
        $messageCount = $this->message_count;
        $lastMessage = $this->latestMessage;
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message_count' => $messageCount,
            'last_message' => $lastMessage ? $lastMessage->content : null,
            'last_message_at' => $this->last_message_at,
            'status' => $this->status
        ];
    }
}
