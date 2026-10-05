<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradeDispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_grading_id', 'student_id', 'dispute_reason', 'supporting_evidence',
        'status', 'reviewed_by', 'resolution_notes', 'adjusted_marks', 'resolved_at'
    ];

    protected $casts = [
        'supporting_evidence' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function questionGrading()
    {
        return $this->belongsTo(QuestionGrading::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isResolved()
    {
        return in_array($this->status, ['resolved', 'rejected']);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
