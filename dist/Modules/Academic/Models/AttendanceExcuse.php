<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceExcuse extends Model
{
    use SoftDeletes;

    protected $table = 'attendance_excuses';

    protected $fillable = [
        'student_id',
        'staff_id',
        'date',
        'period',
        'reason',
        'document',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function staff()
    {
        return $this->belongsTo(\Modules\HR\Models\Staff::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewed_by');
    }
} 