<?php

namespace Modules\Examination\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradingRubric extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'criteria', 'total_points', 'grade_scale', 'is_active'
    ];

    protected $casts = [
        'criteria' => 'array',
        'is_active' => 'boolean',
    ];

    public function questionGradings()
    {
        return $this->hasMany(QuestionGrading::class);
    }

    public function getGradeFromScore($score)
    {
        $percentage = ($score / $this->total_points) * 100;
        
        $gradeScale = json_decode($this->grade_scale, true);
        
        foreach ($gradeScale as $grade => $minScore) {
            if ($percentage >= $minScore) {
                return $grade;
            }
        }
        
        return 'F';
    }

    public function getCriteriaWithScores()
    {
        return collect($this->criteria)->map(function($criterion) {
            return [
                'name' => $criterion['name'],
                'description' => $criterion['description'],
                'max_points' => $criterion['max_points'],
                'weight' => $criterion['weight'] ?? 1
            ];
        });
    }
}
