<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpdActivity extends Model
{
    protected $table = 'nursing_cpd_activities';

    protected $fillable = [
        'school_id',
        'user_id',
        'activity_name',
        'provider',
        'type',
        'activity_date',
        'hours',
        'certificate_path',
        'status',
        'approved_by',
        'approved_at',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'hours' => 'decimal:1',
        'approved_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->whereYear('activity_date', $year);
    }
}
