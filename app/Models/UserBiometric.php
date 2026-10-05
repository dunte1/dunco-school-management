<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBiometric extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'biometric_type',
        'biometric_data',
        'public_key',
        'is_enabled',
        'last_used',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'last_used' => 'datetime',
    ];

    protected $hidden = [
        'biometric_data',
        'public_key',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}