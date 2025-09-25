<?php

namespace App\Models\Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'name',
        'biography',
        'email',
        'website',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
