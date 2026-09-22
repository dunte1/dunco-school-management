<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScenarioQuestion extends Model
{
    protected $table = 'nursing_scenario_questions';

    protected $fillable = [
        'scenario_id',
        'order',
        'question',
        'choices',
        'correct_choice_index',
        'explanation',
        'learning_point',
    ];

    protected $casts = [
        'choices' => 'array',
        'correct_choice_index' => 'integer',
    ];

    public function scenario(): BelongsTo
    {
        return $this->belongsTo(Scenario::class);
    }
}
