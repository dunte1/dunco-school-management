<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferenceArticle extends Model
{
    use SoftDeletes;

    protected $table = 'nursing_reference_articles';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'author',
        'author_id',
        'reviewer',
        'reviewer_id',
        'source',
        'version',
        'review_date',
        'status',
        'is_featured',
        'view_count',
        'tags',
    ];

    protected $casts = [
        'review_date' => 'date',
        'is_featured' => 'boolean',
        'tags' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ReferenceCategory::class, 'category_id');
    }

    public function authorUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    public function reviewerUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewer_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
