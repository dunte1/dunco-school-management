<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $fillable = [
        'staff_id', 'type', 'start_date', 'end_date', 'duration_months', 'on_probation', 'probation_end', 'renewal_reminder', 'promotion_from', 'promotion_to', 'promotion_date', 'old_salary', 'new_salary', 'transfer_from_school', 'transfer_to_school', 'transfer_from_department', 'transfer_to_department'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
} 