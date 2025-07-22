<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class Exam extends Model
{
    protected $table = 'academic_exams';
    
    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'exam_type', // midterm, final, quiz, assignment, project
        'academic_year',
        'term',
        'start_date',
        'end_date',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'is_active',
        'status', // draft, published, ongoing, completed, archived
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'academic_exam_subject', 'exam_id', 'subject_id')
            ->withPivot('exam_date', 'start_time', 'end_time', 'total_marks', 'passing_marks')
            ->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(AcademicClass::class, 'academic_exam_class', 'exam_id', 'class_id')
            ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'published' => 'info',
            'ongoing' => 'warning',
            'completed' => 'success',
            'archived' => 'dark',
            default => 'secondary'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return '<span class="badge badge-' . $this->status_color . '">' . ucfirst($this->status) . '</span>';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    public function scopeByTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())->where('status', 'published');
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where('status', 'published');
    }
} 