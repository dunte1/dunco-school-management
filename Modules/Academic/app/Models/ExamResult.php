<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class ExamResult extends Model
{
    protected $table = 'academic_exam_results';
    
    protected $fillable = [
        'school_id',
        'exam_id',
        'student_id',
        'subject_id',
        'class_id',
        'marks_obtained',
        'total_marks',
        'percentage',
        'grade',
        'remarks',
        'submitted_at',
        'is_active',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function getGradeAttribute($value)
    {
        if (!$value && $this->percentage) {
            return $this->calculateGrade($this->percentage);
        }
        return $value;
    }

    public function setPercentageAttribute($value)
    {
        if ($this->marks_obtained && $this->total_marks) {
            $this->attributes['percentage'] = ($this->marks_obtained / $this->total_marks) * 100;
        } else {
            $this->attributes['percentage'] = $value;
        }
    }

    private function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 35) return 'D';
        return 'F';
    }

    public function getGradeColorAttribute()
    {
        return match($this->grade) {
            'A+' => 'success',
            'A' => 'success',
            'B+' => 'info',
            'B' => 'info',
            'C+' => 'warning',
            'C' => 'warning',
            'D' => 'danger',
            'F' => 'danger',
            default => 'secondary'
        };
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }
} 