<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'school_id'
    ];

    /**
     * Get the borrow records for this member.
     */
    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class, 'member_id');
    }

    /**
     * Get the current borrow records (not returned yet).
     */
    public function currentBorrowRecords()
    {
        return $this->hasMany(BorrowRecord::class, 'member_id')->whereNull('returned_at');
    }

    /**
     * Get overdue borrow records.
     */
    public function overdueBorrowRecords()
    {
        return $this->hasMany(BorrowRecord::class, 'member_id')
            ->whereNull('returned_at')
            ->where('due_at', '<', now());
    }
}