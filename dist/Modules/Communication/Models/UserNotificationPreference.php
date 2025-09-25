<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class UserNotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'channel',
        'category',
        'enabled',
        'sound',
        'vibration',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'sound' => 'boolean',
        'vibration' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 