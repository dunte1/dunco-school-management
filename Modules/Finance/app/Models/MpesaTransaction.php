<?php

namespace Modules\Finance\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MpesaTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type',
        'amount',
        'phone_number',
        'account_reference',
        'transaction_desc',
        'status',
        'checkout_request_id',
        'mpesa_receipt_number',
        'transaction_date',
        'result_code',
        'result_desc',
        'conversation_id',
        'originator_conversation_id',
        'merchant_request_id',
        'completed_at',
        'failed_at',
        'user_id',
        'fee_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
        'transaction_date' => 'datetime',
    ];

    /**
     * Transaction types
     */
    const TYPE_STK_PUSH = 'stk_push';
    const TYPE_C2B = 'c2b';
    const TYPE_B2C = 'b2c';
    const TYPE_REVERSAL = 'reversal';

    /**
     * Transaction statuses
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REVERSED = 'reversed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get transaction type options
     */
    public static function getTransactionTypes()
    {
        return [
            self::TYPE_STK_PUSH => 'STK Push',
            self::TYPE_C2B => 'C2B',
            self::TYPE_B2C => 'B2C',
            self::TYPE_REVERSAL => 'Reversal',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_REVERSED => 'Reversed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Check if transaction is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if transaction is failed
     */
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if transaction is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'KES ' . number_format($this->amount, 2);
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone_number) {
            return 'N/A';
        }
        
        // Format as +254 XXX XXX XXX
        $phone = $this->phone_number;
        if (substr($phone, 0, 3) === '254') {
            $phone = '+254 ' . substr($phone, 3, 3) . ' ' . substr($phone, 6, 3) . ' ' . substr($phone, 9, 3);
        }
        
        return $phone;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_COMPLETED => 'badge-success',
            self::STATUS_FAILED => 'badge-danger',
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_REVERSED => 'badge-info',
            self::STATUS_CANCELLED => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relationship with Fee
     */
    public function fee()
    {
        return $this->belongsTo(\Modules\Finance\app\Models\Fee::class);
    }

    /**
     * Get transaction summary
     */
    public function getTransactionSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'type' => $this->transaction_type,
            'amount' => $this->formatted_amount,
            'phone' => $this->formatted_phone,
            'status' => $this->status,
            'reference' => $this->account_reference,
            'receipt' => $this->mpesa_receipt_number,
            'date' => $this->completed_at?->format('M d, Y H:i') ?? 'N/A',
        ];
    }
} 