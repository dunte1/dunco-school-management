<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\SubjectResource;
use Modules\Academic\Models\StudentDocument;

class HomeworkController extends Controller
{
	public function daily(Request $request)
	{
		$user = $request->user();
		$student = Student::where('user_id', $user->id)->first();
		if (!$student) return response()->json(['assignments' => []]);

		$subjectId = $request->get('subject_id');
		$items = SubjectResource::query()
			->where('type', 'assignment')
			->when($subjectId, fn($q) => $q->where('subject_id', (int) $subjectId))
			->whereHas('subject', function ($q) use ($user) {
				$q->where('school_id', $user->school_id);
			})
			->orderBy('created_at', 'desc')
			->get()
			->map(function ($a) {
				return [
					'id' => $a->id,
					'subject_id' => $a->subject_id,
					'title' => $a->title ?? 'Assignment',
					'description' => $a->description ?? null,
					'file_url' => $a->url ?? null,
					'created_at' => $a->created_at?->toISOString(),
				];
			});

		return response()->json(['assignments' => $items]);
	}

	public function saveDaily(Request $request)
	{
		$request->validate([
			'subject_id' => 'required|integer',
			'title' => 'required|string|max:255',
			'description' => 'nullable|string',
			'url' => 'nullable|url',
			'id' => 'nullable|integer',
		]);
		$user = $request->user();

		$payload = [
			'subject_id' => (int) $request->subject_id,
			'type' => 'assignment',
			'title' => $request->title,
			'url' => $request->url,
			'uploaded_by' => $user->id,
		];

		if ($request->id) {
			$resource = SubjectResource::find($request->id);
			if ($resource) {
				$resource->update($payload);
			}
		} else {
			$resource = SubjectResource::create($payload);
		}

		return response()->json(['message' => 'Saved', 'id' => $resource?->id]);
	}

	public function deleteDaily(Request $request)
	{
		$request->validate([
			'id' => 'required|integer',
		]);
		$resource = SubjectResource::where('id', $request->id)
			->where('type', 'assignment')
			->first();
		if ($resource) {
			$resource->delete();
		}
		return response()->json(['message' => 'Deleted']);
	}

	public function submitted(Request $request)
	{
		$user = $request->user();
		$student = Student::where('user_id', $user->id)->first();
		if (!$student) return response()->json(['submitted' => []]);

		$docs = StudentDocument::where('student_id', $student->id)
			->orderBy('created_at', 'desc')
			->get()
			->map(function ($d) {
				return [
					'id' => $d->id,
					'name' => $d->name ?? $d->file_name,
					'url' => $d->url ?? null,
					'created_at' => $d->created_at?->toISOString(),
				];
			});

		return response()->json(['submitted' => $docs]);
	}
}


