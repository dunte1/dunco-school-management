<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'is_available',
        'school_id'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Get the borrow records for this book.
     */
    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class, 'book_id');
    }

    /**
     * Get the current borrow record (if borrowed).
     */
    public function currentBorrowRecord()
    {
        return $this->hasOne(BorrowRecord::class, 'book_id')->whereNull('returned_at');
    }
}