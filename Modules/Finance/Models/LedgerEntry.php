<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $table = 'ledger_entries';
    
    protected $fillable = [
        'date',
        'account',
        'type',
        'description',
        'debit',
        'credit',
        'reference',
        'related_id',
        'related_type',
    ];

    public function related()
    {
        return $this->morphTo();
    }
} 