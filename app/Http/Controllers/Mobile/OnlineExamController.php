<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Academic\Models\Question;

class OnlineExamController extends Controller
{
	public function getExamQuestions(Request $request)
	{
		$subjectId = (int) $request->get('subject_id');
		$limit = (int) ($request->get('limit') ?: 20);
		$questions = Question::query()
			->when($subjectId, fn($q) => $q->where('subject_id', $subjectId))
			->orderBy('created_at', 'desc')
			->limit($limit)
			->get()
			->map(function ($q) {
				return [
					'id' => $q->id,
					'question' => $q->question_text ?? $q->stem ?? '',
					'options' => $q->options ?? [],
					'marks' => (int) ($q->marks ?? 1),
				];
			});
		return response()->json(['questions' => $questions]);
	}

	public function saveAnswer(Request $request)
	{
		$request->validate([
			'question_id' => 'required|integer',
			'answer' => 'required',
		]);
		$key = $this->answersCacheKey($request);
		$answers = Cache::get($key, []);
		$answers[$request->question_id] = $request->answer;
		Cache::put($key, $answers, now()->addHours(3));
		return response()->json(['saved' => true]);
	}

	public function submitExam(Request $request)
	{
		$subjectId = (int) $request->get('subject_id');
		$key = $this->answersCacheKey($request);
		$answers = Cache::pull($key, []);
		if (empty($answers)) {
			return response()->json(['score' => 0, 'total' => 0, 'percentage' => 0]);
		}
		$questionIds = array_keys($answers);
		$questions = Question::whereIn('id', $questionIds)
			->when($subjectId, fn($q) => $q->where('subject_id', $subjectId))
			->get();
		$total = 0; $score = 0;
		foreach ($questions as $q) {
			$marks = (int) ($q->marks ?? 1);
			$total += $marks;
			if (isset($answers[$q->id]) && $this->isCorrect($q, $answers[$q->id])) {
				$score += $marks;
			}
		}
		$percentage = $total > 0 ? round(($score / $total) * 100, 2) : 0;
		$attemptId = (string) \Illuminate\Support\Str::uuid();
		Cache::put($this->resultCacheKey($request, $attemptId), [
			'score' => $score,
			'total' => $total,
			'percentage' => $percentage,
		], now()->addDays(1));
		return response()->json(['attempt_id' => $attemptId, 'score' => $score, 'total' => $total, 'percentage' => $percentage]);
	}

	public function examResult(Request $request)
	{
		$request->validate(['attempt_id' => 'required|string']);
		$result = Cache::get($this->resultCacheKey($request, $request->attempt_id));
		if (!$result) return response()->json(['score' => 0, 'total' => 0, 'percentage' => 0]);
		return response()->json($result);
	}

	public function getQuizQuestions(Request $request)
	{
		// Alias to exam questions; accepts quiz_id or subject_id
		return $this->getExamQuestions($request);
	}

	public function submitQuiz(Request $request)
	{
		// Alias to submit exam
		return $this->submitExam($request);
	}

	public function quizResults(Request $request)
	{
		// Alias to exam result
		return $this->examResult($request);
	}

	public function resetQuiz(Request $request)
	{
		Cache::forget($this->answersCacheKey($request));
		return response()->json(['reset' => true]);
	}

	private function answersCacheKey(Request $request): string
	{
		return 'online_exam_answers_' . $request->user()->id;
	}

	private function resultCacheKey(Request $request, string $attemptId): string
	{
		return 'online_exam_result_' . $request->user()->id . '_' . $attemptId;
	}

	private function isCorrect($question, $answer): bool
	{
		$correct = $question->answer ?? null;
		if (is_array($correct)) {
			$ansSet = is_array($answer) ? $answer : [$answer];
			return collect($correct)->values()->sort()->all() === collect($ansSet)->values()->sort()->all();
		}
		return (string) $correct === (string) $answer;
	}
}


