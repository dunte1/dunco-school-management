<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Broadcast extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'target_type',
        'target_data',
        'created_by',
        'scheduled_at',
        'sent_at',
        'is_active',
    ];

    protected $casts = [
        'target_data' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(BroadcastRecipient::class);
    }
} 