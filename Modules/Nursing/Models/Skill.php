<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use SoftDeletes;

    protected $table = 'nursing_skills';

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description',
        'learning_objectives',
        'equipment',
        'procedure_reference',
        'safety_considerations',
        'documentation_requirements',
        'assessment_criteria',
        'references',
        'version',
        'review_date',
        'status',
        'author_id',
        'reviewer_id',
        'is_active',
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'equipment' => 'array',
        'references' => 'array',
        'review_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewer_id');
    }

    public function studentSkills(): HasMany
    {
        return $this->hasMany(StudentSkill::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(SkillAssessment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_active', true);
    }

    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
