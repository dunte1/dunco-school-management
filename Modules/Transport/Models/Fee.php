<?php

namespace Modules\Transport\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fee extends Model
{
    protected $table = 'transport_fees';
    
    protected $fillable = [
        'student_id',
        'route_id',
        'amount',
        'due_date',
        'fee_type',
        'description',
        'late_fee',
        'discount',
        'status',
        'payment_date',
        'payment_method',
        'created_by',
        'school_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the student that this fee belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the route that this fee belongs to.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    /**
     * Get the payments for this fee.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'fee_id');
    }

    /**
     * Scope for pending fees.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid fees.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for overdue fees.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope for waived fees.
     */
    public function scopeWaived($query)
    {
        return $query->where('status', 'waived');
    }

    /**
     * Scope for fees by month.
     */
    public function scopeByMonth($query, $month)
    {
        return $query->whereMonth('due_date', $month);
    }

    /**
     * Scope for fees by year.
     */
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('due_date', $year);
    }

    /**
     * Get the total amount including late fees and discounts.
     */
    public function getTotalAmountAttribute()
    {
        return $this->amount + ($this->late_fee ?? 0) - ($this->discount ?? 0);
    }

    /**
     * Get the remaining amount to be paid.
     */
    public function getRemainingAmountAttribute()
    {
        $paidAmount = $this->payments()->sum('amount');
        return max(0, $this->total_amount - $paidAmount);
    }

    /**
     * Check if the fee is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->status === 'overdue' || ($this->status === 'pending' && $this->due_date < now()->toDateString());
    }
}
