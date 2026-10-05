<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTwoFactor extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'phone',
        'email',
        'backup_codes',
        'secret_key',
        'is_enabled',
        'verified_at',
    ];

    protected $casts = [
        'backup_codes' => 'array',
        'is_enabled' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected $hidden = [
        'secret_key',
        'backup_codes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}