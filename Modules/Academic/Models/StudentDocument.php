<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    protected $table = 'academic_student_documents';
    protected $fillable = [
        'student_id',
        'type',
        'file_path',
        'uploaded_at',
        'status',
        'review_note',
    ];
    public $timestamps = true;

    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
} 