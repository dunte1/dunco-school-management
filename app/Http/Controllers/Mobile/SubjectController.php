<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\SubjectResource;

class SubjectController extends Controller
{
	public function list(Request $request)
	{
		$user = $request->user();
		$student = Student::where('user_id', $user->id)->first();
		if (!$student) return response()->json(['subjects' => []]);

		$subjects = Subject::where('school_id', $user->school_id)
			->when($student->class_id, function ($q) use ($student) {
				$q->whereHas('classes', function ($qq) use ($student) {
					$qq->where('academic_classes.id', $student->class_id);
				});
			})
			->orderBy('name')
			->get()
			->map(function ($subject) {
				return [
					'id' => $subject->id,
					'name' => $subject->name,
					'code' => $subject->code,
					'description' => $subject->description,
					'credits' => $subject->credits,
					'is_active' => $subject->is_active,
				];
			});

		return response()->json(['subjects' => $subjects]);
	}

	public function lessons(Request $request)
	{
		$user = $request->user();
		$student = Student::where('user_id', $user->id)->first();
		if (!$student) return response()->json(['lessons' => []]);

		$subjectId = $request->get('subject_id');
		$resources = SubjectResource::query()
			->when($subjectId, fn($q) => $q->where('subject_id', $subjectId))
			->whereHas('subject', function ($q) use ($user) {
				$q->where('school_id', $user->school_id);
			})
			->orderBy('created_at', 'desc')
			->get()
			->map(function ($res) {
				return [
					'id' => $res->id,
					'subject_id' => $res->subject_id,
					'title' => $res->title ?? 'Resource',
					'description' => $res->description,
					'type' => $res->type ?? 'file',
					'url' => $res->url ?? null,
					'created_at' => $res->created_at?->toISOString(),
				];
			});

		return response()->json(['lessons' => $resources]);
	}

	public function syllabus(Request $request)
	{
		// For now, reuse Subject listing as syllabus subjects
		return $this->list($request);
	}

	public function lessonPlan(Request $request)
	{
		// Alias to lessons endpoint to satisfy Android mapping
		return $this->lessons($request);
	}
}


