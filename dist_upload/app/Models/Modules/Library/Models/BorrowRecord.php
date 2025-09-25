<?php

namespace App\Models\Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
