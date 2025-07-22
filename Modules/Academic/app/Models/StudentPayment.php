<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    protected $table = 'student_payments';
    protected $fillable = [
        'student_id',
        'fee_id',
        'amount',
        'payment_date',
        'method',
        'reference',
        'note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fee()
    {
        return $this->belongsTo(StudentFee::class, 'fee_id');
    }
} 