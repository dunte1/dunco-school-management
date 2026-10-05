<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HR\Models\Staff;
use Modules\Academic\Models\Subject;

class TeacherController extends Controller
{
	public function list(Request $request)
	{
		$user = $request->user();
		$teachers = Staff::where('school_id', $user->school_id)
			->where('role', 'teacher')
			->orderBy('name')
			->get()
			->map(function ($t) {
				return [
					'id' => $t->id,
					'name' => $t->name,
					'email' => $t->email,
					'phone' => $t->phone,
					'department' => $t->department ?? null,
					'photo' => $t->photo ?? null,
				];
			});

		return response()->json(['teachers' => $teachers]);
	}

	public function subjects(Request $request)
	{
		$user = $request->user();
		$teacherId = $request->get('teacher_id');
		$subjects = Subject::where('school_id', $user->school_id)
			->when($teacherId, function ($q) use ($teacherId) {
				$q->whereHas('teachers', function ($qq) use ($teacherId) {
					$qq->where('users.id', $teacherId);
				});
			})
			->orderBy('name')
			->get()
			->map(function ($s) {
				return [
					'id' => $s->id,
					'name' => $s->name,
					'code' => $s->code,
				];
			});

		return response()->json(['subjects' => $subjects]);
	}
}


