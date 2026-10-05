<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\StudentDocument;

class DocumentController extends Controller
{
	public function list(Request $request)
	{
		$user = $request->user();
		$student = Student::where('user_id', $user->id)->first();
		if (!$student) return response()->json(['documents' => []]);

		$docs = StudentDocument::where('student_id', $student->id)
			->orderBy('created_at', 'desc')
			->get()
			->map(function ($d) {
				return [
					'id' => $d->id,
					'name' => $d->name ?? $d->file_name,
					'type' => $d->type ?? 'file',
					'url' => $d->url ?? ($d->path ? url(Storage::url($d->path)) : null),
					'created_at' => $d->created_at?->toISOString(),
				];
			});

		return response()->json(['documents' => $docs]);
	}

	public function upload(Request $request)
	{
		$request->validate([
			'file' => 'required|file|max:10240',
			'name' => 'nullable|string|max:255',
		]);
		$user = $request->user();
		$student = Student::where('user_id', $user->id)->first();
		if (!$student) return response()->json(['message' => 'Not a student'], 422);

		$path = $request->file('file')->store('student-documents/'.$student->id, 'public');
		$doc = StudentDocument::create([
			'student_id' => $student->id,
			'name' => $request->get('name') ?: $request->file('file')->getClientOriginalName(),
			'path' => $path,
			'url' => Storage::disk('public')->url($path),
			'type' => $request->file('file')->getClientOriginalExtension(),
		]);

		return response()->json([
			'message' => 'Uploaded',
			'document' => [
				'id' => $doc->id,
				'name' => $doc->name,
				'url' => $doc->url,
				'type' => $doc->type,
			],
		]);
	}
}


