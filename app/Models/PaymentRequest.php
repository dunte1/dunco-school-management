<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'merchant_request_id',
        'checkout_request_id',
        'amount',
        'phone_number',
        'account_reference',
        'transaction_desc',
        'status',
        'mpesa_receipt_number',
        'failure_reason',
        'school_id',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(\Modules\Core\Entities\School::class);
    }
}
