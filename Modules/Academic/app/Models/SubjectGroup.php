<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectGroup extends Model
{
    protected $table = 'subject_groups';
    protected $fillable = ['name', 'description'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_group_subject', 'group_id', 'subject_id');
    }
}