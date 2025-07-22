<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'description',
    ];

    public function programs()
    {
        return $this->belongsToMany(\Modules\Academic\app\Models\Program::class, 'fee_program');
    }

    public function students()
    {
        return $this->belongsToMany(\Modules\Academic\app\Models\Student::class, 'fee_student')->withPivot('paid');
    }
} 