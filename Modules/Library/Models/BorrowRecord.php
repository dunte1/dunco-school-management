<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    protected $fillable = [
        'book_id',
        'member_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'fine',
        'school_id'
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'fine' => 'decimal:2',
    ];

    /**
     * Get the book for this borrow record.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Get the member for this borrow record.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Check if this borrow record is overdue.
     */
    public function isOverdue()
    {
        return $this->returned_at === null && $this->due_at < now();
    }

    /**
     * Mark this borrow record as returned.
     */
    public function markAsReturned()
    {
        $this->returned_at = now();
        $this->status = 'returned';
        $this->save();

        // Update book availability
        if ($this->book) {
            $this->book->is_available = true;
            $this->book->save();
        }
    }
}