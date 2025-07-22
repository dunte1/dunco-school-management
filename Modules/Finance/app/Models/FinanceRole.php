<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceRole extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'finance_role_user');
    }
} 