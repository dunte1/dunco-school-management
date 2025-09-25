<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'name',
        'account_number',
        'bank_name',
        'balance',
    ];

    public function outgoingTransfers()
    {
        return $this->hasMany(BankTransfer::class, 'from_account_id');
    }

    public function incomingTransfers()
    {
        return $this->hasMany(BankTransfer::class, 'to_account_id');
    }
} 