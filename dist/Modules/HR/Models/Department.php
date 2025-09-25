<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = ['name', 'type', 'school_id'];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
} 