<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\Scenario;
use Modules\Nursing\Models\ScenarioQuestion;
use Modules\Nursing\Models\ScenarioAttempt;

class ScenarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Scenario::published()->with('author');

        if ($request->filled('category')) {
            $query->forCategory($request->category);
        }
        if ($request->filled('difficulty')) {
            $query->forDifficulty($request->difficulty);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $scenarios = $query->latest()->paginate(12);

        return Inertia::render('Nursing/Scenarios/Index', compact('scenarios'));
    }

    public function show(Scenario $scenario)
    {
        $scenario->load('author');
        $questionCount = $scenario->questions()->count();

        $user = Auth::user();
        $student = $user->academicStudent;
        $previousAttempts = $student
            ? ScenarioAttempt::where('scenario_id', $scenario->id)
                ->where('student_id', $student->id)
                ->where('is_completed', true)
                ->count()
            : 0;

        return Inertia::render('Nursing/Scenarios/Show', compact('scenario', 'questionCount', 'previousAttempts'));
    }

    public function create()
    {
        return Inertia::render('Nursing/Scenarios/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'category' => 'required|in:medical_surgical,maternal_health,child_health,community_health,emergency,mental_health,geriatric,perioperative,other',
            'patient_information' => 'nullable|array',
            'patient_history' => 'nullable|array',
            'observations' => 'nullable|array',
            'learning_objectives' => 'nullable|array',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.choices' => 'required|array|min:2',
            'questions.*.correct_choice_index' => 'required|integer|min:0',
            'questions.*.explanation' => 'nullable|string',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['status'] = 'published';

        $scenario = Scenario::create(collect($validated)->except('questions')->toArray());

        foreach ($validated['questions'] as $index => $question) {
            ScenarioQuestion::create([
                'scenario_id' => $scenario->id,
                'order' => $index + 1,
                'question' => $question['question'],
                'choices' => $question['choices'],
                'correct_choice_index' => $question['correct_choice_index'],
                'explanation' => $question['explanation'] ?? null,
            ]);
        }

        return redirect()->route('nursing.scenarios.show', $scenario)
            ->with('success', 'Scenario created successfully.');
    }

    public function attempt(Scenario $scenario)
    {
        $scenario->load('questions');

        return Inertia::render('Nursing/Scenarios/Attempt', compact('scenario'));
    }

    public function submit(Request $request, Scenario $scenario)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $user = Auth::user();
        $student = $user->academicStudent;

        if (!$student) {
            return back()->with('error', 'No student profile found.');
        }

        $questions = $scenario->questions()->orderBy('order')->get();
        $answers = $request->answers;
        $score = 0;

        foreach ($questions as $index => $question) {
            if (isset($answers[$index]) && $answers[$index] == $question->correct_choice_index) {
                $score++;
            }
        }

        $totalQuestions = $questions->count();
        $percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;

        $attempt = ScenarioAttempt::create([
            'scenario_id' => $scenario->id,
            'student_id' => $student->id,
            'answers' => $answers,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'is_completed' => true,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        return redirect()->route('nursing.scenarios.results', ['scenario' => $scenario, 'attempt' => $attempt->id])
            ->with('success', 'Scenario completed!');
    }

    public function results(Scenario $scenario, Request $request)
    {
        $user = Auth::user();
        $student = $user->academicStudent;

        $attempt = ScenarioAttempt::where('scenario_id', $scenario->id)
            ->where('student_id', $student?->id)
            ->latest()
            ->first();

        $scenario->load('questions');

        return Inertia::render('Nursing/Scenarios/Results', compact('scenario', 'attempt'));
    }
}
