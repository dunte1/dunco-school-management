<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $table = 'student_fees';
    protected $fillable = [
        'student_id',
        'category',
        'amount',
        'status',
        'due_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payments()
    {
        return $this->hasMany(StudentPayment::class, 'fee_id');
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('amount');
    }

    public function getOutstandingAmountAttribute()
    {
        return max(0, $this->amount - $this->total_paid);
    }
} 