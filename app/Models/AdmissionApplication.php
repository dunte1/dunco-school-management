<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    protected $fillable = [
        'application_number', 'admission_cycle_id', 'status',
        'first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender',
        'nationality', 'national_id', 'parent_data', 'academic_data', 'emergency_data',
        'field_values', 'document_paths', 'desired_class', 'notes', 'rejection_reason',
        'reviewed_by', 'reviewed_at', 'interview_date', 'interview_completed_at',
        'accepted_at', 'enrolled_at', 'enrolled_student_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date', 'parent_data' => 'array', 'academic_data' => 'array',
        'emergency_data' => 'array', 'field_values' => 'array', 'document_paths' => 'array',
        'reviewed_at' => 'datetime', 'interview_date' => 'datetime',
        'interview_completed_at' => 'datetime', 'accepted_at' => 'datetime', 'enrolled_at' => 'datetime',
    ];

    public function cycle() { return $this->belongsTo(AdmissionCycle::class, 'admission_cycle_id'); }
    public function notes() { return $this->hasMany(AdmissionApplicationNote::class)->orderBy('created_at', 'desc'); }
    public function logs() { return $this->hasMany(AdmissionApplicationLog::class)->orderBy('created_at', 'desc'); }

    public function scopeByStatus($q, $s) { return $q->where('status', $s); }
    public function scopeByCycle($q, $c) { return $q->where('admission_cycle_id', $c); }

    public function getFullNameAttribute(): string { return $this->first_name . ' ' . $this->last_name; }

    public static function generateApplicationNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('ADM-%s-%06d', $year, $last);
    }

    public function transitionTo(string $newStatus, string $action, $userId = null, ?string $details = null): bool
    {
        $transitions = [
            'draft' => ['submitted'], 'submitted' => ['under_review', 'rejected', 'withdrawn'],
            'under_review' => ['shortlisted', 'rejected', 'withdrawn'],
            'shortlisted' => ['interview_scheduled', 'rejected', 'withdrawn'],
            'interview_scheduled' => ['interviewed', 'rejected', 'withdrawn'],
            'interviewed' => ['accepted', 'rejected', 'waitlisted', 'withdrawn'],
            'waitlisted' => ['accepted', 'rejected', 'withdrawn'],
            'accepted' => ['enrolled', 'rejected', 'withdrawn'],
        ];
        if (!in_array($newStatus, $transitions[$this->status] ?? [])) return false;
        $old = $this->status;
        $updates = ['status' => $newStatus];
        if ($newStatus === 'under_review') { $updates['reviewed_by'] = $userId; $updates['reviewed_at'] = now(); }
        if ($newStatus === 'accepted') $updates['accepted_at'] = now();
        if ($newStatus === 'enrolled') $updates['enrolled_at'] = now();
        if ($newStatus === 'rejected') $updates['rejection_reason'] = $details;
        $this->update($updates);
        $this->logs()->create(['user_id' => $userId, 'action' => $action, 'old_status' => $old, 'new_status' => $newStatus, 'details' => $details]);
        return true;
    }
}
