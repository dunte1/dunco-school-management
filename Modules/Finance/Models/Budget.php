<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'category',
        'period',
        'amount',
        'type',
    ];
} 