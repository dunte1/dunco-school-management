<?php

namespace App\Models\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class SessionTemplate extends Model
{
    protected $fillable = [
        'school_id', 'name', 'description', 'default_start_time', 'default_end_time', 'is_active'
    ];

    public function rules()
    {
        return $this->hasMany(SessionTemplateRule::class);
    }

    public function classes()
    {
        return $this->belongsToMany(\Modules\Academic\Models\AcademicClass::class, 'class_session_template', 'session_template_id', 'class_id')
            ->withPivot('day_of_week', 'start_date', 'end_date');
    }
}
