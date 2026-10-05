<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\SubjectResource;
use Modules\Academic\Models\SubjectFeedback;
use Modules\Academic\Models\Question;

class CourseController extends Controller
{
	public function index(Request $request)
	{
		$user = $request->user();
		$courses = Subject::where('school_id', $user->school_id)
			->orderBy('name')
			->get()
			->map(function ($s) {
				return [
					'id' => $s->id,
					'name' => $s->name,
					'code' => $s->code,
					'description' => $s->description,
					'credits' => $s->credits,
				];
			});
		return response()->json(['courses' => $courses]);
	}

	public function detail(Request $request)
	{
		$request->validate(['id' => 'required|integer']);
		$course = Subject::find($request->id);
		if (!$course) return response()->json(['course' => null]);
		return response()->json([
			'course' => [
				'id' => $course->id,
				'name' => $course->name,
				'code' => $course->code,
				'description' => $course->description,
				'credits' => $course->credits,
			],
		]);
	}

	public function curriculum(Request $request)
	{
		$subjectId = $request->get('course_id') ?: $request->get('subject_id');
		$query = SubjectResource::query()->where('type', 'lesson');
		if ($subjectId) $query->where('subject_id', (int) $subjectId);
		$items = $query->orderBy('created_at', 'desc')->get()->map(function ($r) {
			return [
				'id' => $r->id,
				'title' => $r->title ?? 'Lesson',
				'description' => $r->description ?? null,
				'type' => $r->type,
				'url' => $r->url,
				'created_at' => $r->created_at?->toISOString(),
			];
		});
		return response()->json(['curriculum' => $items]);
	}

	public function reviews(Request $request)
	{
		$subjectId = $request->get('course_id') ?: $request->get('subject_id');
		$reviews = SubjectFeedback::query()
			->when($subjectId, fn($q) => $q->where('subject_id', (int) $subjectId))
			->orderBy('created_at', 'desc')
			->get()
			->map(function ($f) {
				return [
					'id' => $f->id,
					'user_id' => $f->user_id,
					'rating' => (int) ($f->rating ?? 0),
					'comment' => $f->comment ?? '',
					'created_at' => $f->created_at?->toISOString(),
				];
			});
		return response()->json(['reviews' => $reviews]);
	}

	public function questions(Request $request)
	{
		$subjectId = $request->get('course_id') ?: $request->get('subject_id');
		$questions = Question::query()
			->when($subjectId, fn($q) => $q->where('subject_id', (int) $subjectId))
			->orderBy('created_at', 'desc')
			->limit(50)
			->get()
			->map(function ($q) {
				return [
					'id' => $q->id,
					'stem' => $q->question_text ?? $q->stem ?? '',
					'options' => $q->options ?? [],
					'answer' => $q->answer ?? null,
					'difficulty' => $q->difficulty ?? 'normal',
				];
			});
		return response()->json(['questions' => $questions]);
	}

	public function saveAssignment(Request $request)
	{
		$request->validate([
			'course_id' => 'required|integer',
			'title' => 'required|string|max:255',
			'description' => 'nullable|string',
			'url' => 'nullable|url',
		]);
		$user = $request->user();
		$resource = SubjectResource::create([
			'subject_id' => (int) $request->course_id,
			'type' => 'assignment',
			'title' => $request->title,
			'url' => $request->url,
			'uploaded_by' => $user->id,
		]);
		return response()->json(['message' => 'Saved', 'id' => $resource->id]);
	}

	public function rating(Request $request)
	{
		$request->validate([
			'course_id' => 'required|integer',
			'rating' => 'required|integer|min:1|max:5',
			'comment' => 'nullable|string',
		]);
		$feedback = SubjectFeedback::create([
			'subject_id' => (int) $request->course_id,
			'user_id' => $request->user()->id,
			'rating' => (int) $request->rating,
			'comment' => $request->comment,
		]);
		return response()->json(['message' => 'Thanks for your feedback', 'id' => $feedback->id]);
	}

	public function payment(Request $request)
	{
		$request->validate([
			'course_id' => 'required|integer',
			'amount' => 'required|numeric|min:0.01',
			'method' => 'required|string',
		]);
		return response()->json([
			'status' => 1,
			'message' => 'Course payment requested',
			'transaction_id' => (string) \Illuminate\Support\Str::uuid(),
		]);
	}
}


