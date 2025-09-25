<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_date',
        'method',
        'status',
        'reference',
        'mpesa_transaction_code',
        'mpesa_phone',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
} 