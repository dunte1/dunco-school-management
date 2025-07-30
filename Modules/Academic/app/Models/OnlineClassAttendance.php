<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class OnlineClassAttendance extends Model
{
    public $joined_at;
    public $left_at;
    
    protected $fillable = [
        'online_class_id',
        'user_id',
        'joined_at',
        'left_at',
        'status',
        'duration_minutes',
        'notes'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime'
    ];

    public function onlineClass(): BelongsTo
    {
        return $this->belongsTo(OnlineClass::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationAttribute(): int
    {
        if (!$this->joined_at) return 0;
        
        $endTime = $this->left_at ?? now();
        return $this->joined_at->diffInMinutes($endTime);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }
}
