<?php

namespace Modules\ChatBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatBotMessage extends Model
{
    protected $table = 'chatbot_messages';
    
    protected $fillable = [
        'conversation_id',
        'type',
        'content',
        'metadata',
        'ai_provider',
        'tokens_used',
        'cost',
        'processed_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'cost' => 'decimal:6'
    ];

    /**
     * Get the conversation that owns the message
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatBotConversation::class, 'conversation_id');
    }

    /**
     * Scope for user messages
     */
    public function scopeUser($query)
    {
        return $query->where('type', 'user');
    }

    /**
     * Scope for assistant messages
     */
    public function scopeAssistant($query)
    {
        return $query->where('type', 'assistant');
    }

    /**
     * Scope for system messages
     */
    public function scopeSystem($query)
    {
        return $query->where('type', 'system');
    }

    /**
     * Get message type display name
     */
    public function getTypeDisplayAttribute()
    {
        return ucfirst($this->type);
    }

    /**
     * Get formatted content
     */
    public function getFormattedContentAttribute()
    {
        return nl2br(e($this->content));
    }

    /**
     * Get processing time
     */
    public function getProcessingTimeAttribute()
    {
        if ($this->processed_at) {
            return $this->created_at->diffInMilliseconds($this->processed_at);
        }
        return null;
    }

    /**
     * Check if message is from user
     */
    public function isUser()
    {
        return $this->type === 'user';
    }

    /**
     * Check if message is from assistant
     */
    public function isAssistant()
    {
        return $this->type === 'assistant';
    }

    /**
     * Check if message is from system
     */
    public function isSystem()
    {
        return $this->type === 'system';
    }
}
