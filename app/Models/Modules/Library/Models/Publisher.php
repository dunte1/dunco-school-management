<?php

namespace App\Models\Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = [
        'name',
        'description',
        'email',
        'phone',
        'website',
        'address',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
