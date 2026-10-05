<?php

namespace Modules\Hostel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'name',
        'student_id',
        'email',
        'phone',
        'hostel_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'status',
        'emergency_contact',
        'emergency_phone',
        'created_by',
        'school_id'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the table name for the model.
     */
    public function getTable()
    {
        return 'hostel_students';
    }

    /**
     * Get the hostel that the student belongs to.
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class, 'hostel_id');
    }

    /**
     * Get the room that the student is allocated to.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Get the room allocation for this student.
     */
    public function roomAllocation(): HasOne
    {
        return $this->hasOne(RoomAllocation::class, 'student_id');
    }

    /**
     * Get the hostel fees for this student.
     */
    public function fees(): HasMany
    {
        return $this->hasMany(HostelFee::class, 'student_id');
    }

    /**
     * Get the hostel issues reported by this student.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(HostelIssue::class, 'student_id');
    }

    /**
     * Get the leave requests for this student.
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'student_id');
    }

    /**
     * Get the user who created this student record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope to get only active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only inactive students.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to get graduated students.
     */
    public function scopeGraduated($query)
    {
        return $query->where('status', 'graduated');
    }

    /**
     * Scope to get transferred students.
     */
    public function scopeTransferred($query)
    {
        return $query->where('status', 'transferred');
    }

    /**
     * Check if student is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if student has checked out.
     */
    public function hasCheckedOut(): bool
    {
        return !is_null($this->check_out_date);
    }

    /**
     * Get the duration of stay in days.
     */
    public function getStayDurationAttribute(): int
    {
        $endDate = $this->check_out_date ?? now();
        return $this->check_in_date->diffInDays($endDate);
    }

    /**
     * Get the student's full status with additional context.
     */
    public function getFullStatusAttribute(): string
    {
        $status = ucfirst($this->status);
        
        if ($this->hasCheckedOut()) {
            $status .= ' (Checked Out)';
        }
        
        return $status;
    }

    /**
     * Get the total fees paid by this student.
     */
    public function getTotalFeesPaidAttribute(): float
    {
        return $this->fees()->where('status', 'paid')->sum('amount');
    }

    /**
     * Get the total fees due by this student.
     */
    public function getTotalFeesDueAttribute(): float
    {
        return $this->fees()->where('status', 'pending')->sum('amount');
    }

    /**
     * Check if student has pending fees.
     */
    public function hasPendingFees(): bool
    {
        return $this->fees()->where('status', 'pending')->exists();
    }

    /**
     * Check if student has any open issues.
     */
    public function hasOpenIssues(): bool
    {
        return $this->issues()->where('status', 'open')->exists();
    }
}
