<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectResource extends Model
{
    protected $table = 'subject_resources';
    protected $fillable = ['subject_id', 'type', 'title', 'url', 'file_path', 'uploaded_by'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
} 