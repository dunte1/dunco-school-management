<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $fillable = [
        'book_id',
        'member_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'notes',
        'fine_amount',
        'school_id'
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'fine_amount' => 'decimal:2',
    ];

    /**
     * Get the book that was borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Get the member who borrowed the book.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Check if the book is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'borrowed' && 
               $this->due_at && 
               $this->due_at->isPast();
    }

    /**
     * Check if the book has been returned.
     */
    public function isReturned()
    {
        return $this->status === 'returned' && $this->returned_at !== null;
    }
}
