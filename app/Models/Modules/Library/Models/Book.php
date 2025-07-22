<?php

namespace App\Models\Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'isbn',
        'barcode',
        'edition',
        'year',
        'category_id',
        'author_id',
        'publisher_id',
        'cover_image',
        'description',
        'status',
        'ebook_file_path',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
