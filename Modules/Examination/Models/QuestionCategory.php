<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionCategory extends Model
{
    use SoftDeletes;

    protected $table = 'question_categories';

    protected $fillable = ['name', 'code', 'description', 'parent_id', 'subject', 'topic', 'difficulty', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(QuestionCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(QuestionCategory::class, 'parent_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'category_id');
    }
}
