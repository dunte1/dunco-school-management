<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
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