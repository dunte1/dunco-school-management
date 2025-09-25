<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'subject',
        'body',
        'is_group',
        'group_id',
        'scheduled_at',
    ];

    protected $casts = [
        'is_group' => 'boolean',
        'scheduled_at' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(MessageRecipient::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
