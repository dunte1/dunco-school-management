<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'amount',
        'transfer_date',
        'reference',
    ];

    public function fromAccount()
    {
        return $this->belongsTo(BankAccount::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(BankAccount::class, 'to_account_id');
    }
} 