<?php

namespace App\Models\Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
