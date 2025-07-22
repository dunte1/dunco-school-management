<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'student_id',
        'total_amount',
        'due_date',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(\Modules\Academic\Models\Student::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
} 