<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;

class FinanceSetting extends Model
{
    protected $table = 'finance_settings';
    protected $fillable = ['key', 'value'];
    public $timestamps = false;
} 