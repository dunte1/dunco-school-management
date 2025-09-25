<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    protected $fillable = [
        'student_fee_id',
        'checkout_request_id',
        'status',
    ];

    public function studentFee()
    {
        return $this->belongsTo(\Modules\Academic\Models\StudentFee::class);
    }
} 