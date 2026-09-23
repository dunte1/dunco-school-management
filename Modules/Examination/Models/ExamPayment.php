<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamPayment extends Model
{
    protected $table = 'exam_payments';

    protected $fillable = [
        'exam_id',
        'student_id',
        'school_id',
        'amount',
        'currency',
        'method',
        'status',
        'reference',
        'transaction_code',
        'phone',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\Modules\Academic\Models\Student::class, 'student_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function isPaid(): bool
    {
        return $this->status === 'completed';
    }
}
