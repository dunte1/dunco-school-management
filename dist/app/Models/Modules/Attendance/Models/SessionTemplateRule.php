<?php

namespace App\Models\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class SessionTemplateRule extends Model
{
    protected $fillable = [
        'session_template_id', 'rule_type', 'value', 'description'
    ];

    public function template()
    {
        return $this->belongsTo(SessionTemplate::class, 'session_template_id');
    }
}
