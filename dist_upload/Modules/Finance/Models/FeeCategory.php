<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function fees()
    {
        return $this->hasMany(Fee::class, 'fee_category_id');
    }
} 