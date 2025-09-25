<?php

namespace Modules\ChatBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'chatbot_messages';

    protected $fillable = [
        'conversation_id',
        'document_id',
        'content',
        'role',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the conversation this message belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the document this message is related to
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Scope to get user messages
     */
    public function scopeUserMessages($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope to get assistant messages
     */
    public function scopeAssistantMessages($query)
    {
        return $query->where('role', 'assistant');
    }

    /**
     * Scope to get system messages
     */
    public function scopeSystemMessages($query)
    {
        return $query->where('role', 'system');
    }

    /**
     * Check if message is from user
     */
    public function isUserMessage()
    {
        return $this->role === 'user';
    }

    /**
     * Check if message is from assistant
     */
    public function isAssistantMessage()
    {
        return $this->role === 'assistant';
    }

    /**
     * Check if message is from system
     */
    public function isSystemMessage()
    {
        return $this->role === 'system';
    }

    /**
     * Get formatted timestamp
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Get message preview
     */
    public function getPreviewAttribute()
    {
        return substr($this->content, 0, 100) . (strlen($this->content) > 100 ? '...' : '');
    }
} 