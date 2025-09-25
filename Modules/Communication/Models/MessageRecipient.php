<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class MessageRecipient extends Model
{
    protected $fillable = [
        'message_id',
        'recipient_id',
        'is_read',
        'read_at',
        'is_starred',
        'is_deleted',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_deleted' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }
}
