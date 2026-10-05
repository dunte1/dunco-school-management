<?php

namespace Modules\ChatBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatBotFeedback extends Model
{
    protected $table = 'chatbot_feedback';
    
    protected $fillable = [
        'message_id',
        'conversation_id',
        'user_id',
        'rating',
        'comment',
        'feedback_type',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'rating' => 'integer'
    ];

    /**
     * Get the user that owns the feedback
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the message that the feedback is for
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatBotMessage::class);
    }

    /**
     * Get the conversation that the feedback is for
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatBotConversation::class);
    }

    /**
     * Scope for positive feedback
     */
    public function scopePositive($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Scope for negative feedback
     */
    public function scopeNegative($query)
    {
        return $query->where('rating', '<=', 2);
    }

    /**
     * Scope for neutral feedback
     */
    public function scopeNeutral($query)
    {
        return $query->where('rating', 3);
    }

    /**
     * Scope for message feedback
     */
    public function scopeMessageFeedback($query)
    {
        return $query->where('feedback_type', 'message');
    }

    /**
     * Scope for conversation feedback
     */
    public function scopeConversationFeedback($query)
    {
        return $query->where('feedback_type', 'conversation');
    }

    /**
     * Get feedback rating as stars
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get feedback sentiment
     */
    public function getSentimentAttribute()
    {
        if ($this->rating >= 4) {
            return 'positive';
        } elseif ($this->rating <= 2) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }
}
