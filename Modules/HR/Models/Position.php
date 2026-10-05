<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'title', 'description', 'department_id', 'salary_range', 'level', 'is_active', 'school_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
