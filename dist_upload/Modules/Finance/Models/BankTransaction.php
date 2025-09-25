<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'description',
        'reference',
        'status',
        'matched_payment_id',
    ];

    public function matchedPayment()
    {
        return $this->belongsTo(Payment::class, 'matched_payment_id');
    }
} 