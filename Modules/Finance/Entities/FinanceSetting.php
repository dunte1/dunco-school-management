<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;

class FinanceSetting extends Model
{
    protected $table = 'finance_settings';
    protected $fillable = ['settings'];
    protected $casts = [
        'settings' => 'array',
    ];
    public $timestamps = false;
} 