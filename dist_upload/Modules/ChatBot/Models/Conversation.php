<?php

namespace Modules\ChatBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    protected $table = 'chatbot_conversations';

    protected $fillable = [
        'user_id',
        'session_id',
        'is_active',
        'title',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the messages for this conversation
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the user who owns this conversation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the latest message in this conversation
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * Scope to get active conversations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get conversations by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get conversation summary
     */
    public function getSummaryAttribute()
    {
        $firstMessage = $this->messages()->first();
        return $firstMessage ? substr($firstMessage->content, 0, 100) . '...' : 'No messages';
    }

    /**
     * Get message count
     */
    public function getMessageCountAttribute()
    {
        return $this->messages()->count();
    }
} 