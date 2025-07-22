<?php

namespace App\Models\Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
