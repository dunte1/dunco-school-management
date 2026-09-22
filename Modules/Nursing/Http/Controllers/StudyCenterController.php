<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Nursing\Models\Skill;
use Modules\Nursing\Models\SkillCategory;
use Modules\Nursing\Models\ReferenceArticle;
use Modules\Nursing\Models\ReferenceCategory;

class StudyCenterController extends Controller
{
    public function index()
    {
        $categories = SkillCategory::where('is_active', true)
            ->withCount('skills')
            ->orderBy('sort_order')
            ->get();

        $referenceCategories = ReferenceCategory::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('articles')
            ->orderBy('sort_order')
            ->get();

        $featuredArticles = ReferenceArticle::published()
            ->featured()
            ->take(5)
            ->get();

        return Inertia::render('Nursing/Study/Index', compact('categories', 'referenceCategories', 'featuredArticles'));
    }

    public function flashcards(Request $request)
    {
        $query = Skill::published()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $skills = $query->inRandomOrder()->take(20)->get();

        return Inertia::render('Nursing/Study/Flashcards', compact('skills'));
    }

    public function quizzes(Request $request)
    {
        $skills = Skill::published()->with('category')->get();
        $categories = SkillCategory::where('is_active', true)->orderBy('sort_order')->get();

        return Inertia::render('Nursing/Study/Quizzes', compact('skills', 'categories'));
    }

    public function submitQuiz(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'skill_ids' => 'required|array',
        ]);

        $correct = 0;
        $total = count($request->skill_ids);
        $details = [];

        foreach ($request->skill_ids as $index => $skillId) {
            $skill = Skill::find($skillId);
            $answer = $request->answers[$index] ?? null;
            $isCorrect = false;

            if ($skill && $answer !== null) {
                $isCorrect = (bool) $answer;
                if ($isCorrect) {
                    $correct++;
                }
            }

            $details[] = [
                'skill_id' => $skillId,
                'skill_name' => $skill?->name ?? 'Unknown',
                'answered_correctly' => $isCorrect,
            ];
        }

        $percentage = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        return response()->json([
            'score' => $correct,
            'total' => $total,
            'percentage' => $percentage,
            'details' => $details,
        ]);
    }
}
