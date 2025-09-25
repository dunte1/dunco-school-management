<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leaves';
    protected $fillable = [
        'staff_id', 'type', 'start_date', 'end_date', 'days', 'status', 'reason', 'approved_by', 'approved_at'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'type', 'name');
    }
} 