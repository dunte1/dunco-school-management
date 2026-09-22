<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scenario extends Model
{
    use SoftDeletes;

    protected $table = 'nursing_scenarios';

    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'category',
        'patient_information',
        'patient_history',
        'observations',
        'learning_objectives',
        'references',
        'status',
        'author_id',
        'version',
        'is_active',
    ];

    protected $casts = [
        'patient_information' => 'array',
        'patient_history' => 'array',
        'observations' => 'array',
        'learning_objectives' => 'array',
        'references' => 'array',
        'is_active' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ScenarioQuestion::class, 'scenario_id')->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ScenarioAttempt::class, 'scenario_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_active', true);
    }

    public function scopeForCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }
}
