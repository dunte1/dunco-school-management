<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectCustomField extends Model
{
    protected $table = 'subject_custom_fields';
    protected $fillable = ['subject_id', 'field_name', 'field_value', 'field_type'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
} 