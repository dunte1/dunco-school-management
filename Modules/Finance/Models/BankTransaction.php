<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $table = 'bank_transactions';
    
    protected $fillable = [
        'date',
        'amount',
        'description',
        'reference',
        'status',
    ];

    public function matchedPayment()
    {
        return $this->belongsTo(Payment::class, 'matched_payment_id');
    }
} 