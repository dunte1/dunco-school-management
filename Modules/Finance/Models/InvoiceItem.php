<?php

namespace Modules\Finance\Models;


use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'fee_id',
        'amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
} 