<?php

namespace Modules\Academic\app\Models;

use Illuminate\Database\Eloquent\Model;

class GradingScale extends Model
{
    protected $fillable = [
        'name', // e.g., Default, Science, Arts
        'description',
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
} 