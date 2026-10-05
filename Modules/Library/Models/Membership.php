<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'member_id',
        'membership_type',
        'start_date',
        'end_date',
        'status',
        'notes',
        'school_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the member that owns the membership.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Check if membership is active.
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->end_date && 
               $this->end_date->isFuture();
    }

    /**
     * Check if membership is expired.
     */
    public function isExpired()
    {
        return $this->end_date && $this->end_date->isPast();
    }
}
