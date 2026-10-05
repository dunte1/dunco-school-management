<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hostel\Models\LeaveRequest;
use Modules\Hostel\Models\HostelVisitor;
use Modules\Academic\Models\Student;

class LeaveVisitorController extends Controller
{
	public function leaveList(Request $request)
	{
		$user = $request->user();
		$student = Student::where('user_id', $user->id)->first();
		if (!$student) return response()->json(['leaves' => []]);
		$leaves = LeaveRequest::where('student_id', $user->id)
			->orderBy('from_date', 'desc')
			->get()
			->map(function ($l) {
				return [
					'id' => $l->id,
					'reason' => $l->reason,
					'from_date' => optional($l->from_date)->format('Y-m-d'),
					'to_date' => optional($l->to_date)->format('Y-m-d'),
					'status' => $l->status,
					'emergency_contact' => $l->emergency_contact,
				];
			});
		return response()->json(['leaves' => $leaves]);
	}

	public function leaveApply(Request $request)
	{
		$request->validate([
			'reason' => 'required|string',
			'from_date' => 'required|date',
			'to_date' => 'required|date|after_or_equal:from_date',
			'emergency_contact' => 'nullable|string',
		]);
		$user = $request->user();
		$leave = LeaveRequest::create([
			'student_id' => $user->id,
			'reason' => $request->reason,
			'from_date' => $request->from_date,
			'to_date' => $request->to_date,
			'emergency_contact' => $request->emergency_contact,
			'status' => 'pending',
		]);
		return response()->json(['message' => 'Leave applied', 'id' => $leave->id]);
	}

	public function leaveUpdate(Request $request)
	{
		$request->validate([
			'id' => 'required|integer',
			'reason' => 'nullable|string',
			'from_date' => 'nullable|date',
			'to_date' => 'nullable|date',
			'emergency_contact' => 'nullable|string',
			'status' => 'nullable|string|in:pending,approved,rejected,cancelled',
		]);
		$user = $request->user();
		$leave = LeaveRequest::where('id', $request->id)->where('student_id', $user->id)->first();
		if (!$leave) return response()->json(['message' => 'Not found'], 404);
		$leave->update(array_filter($request->only(['reason','from_date','to_date','emergency_contact','status'])));
		return response()->json(['message' => 'Updated']);
	}

	public function leaveDelete(Request $request)
	{
		$request->validate(['id' => 'required|integer']);
		$user = $request->user();
		LeaveRequest::where('id', $request->id)->where('student_id', $user->id)->delete();
		return response()->json(['deleted' => true]);
	}

	public function visitors(Request $request)
	{
		$user = $request->user();
		$visitors = HostelVisitor::where('student_id', $user->id)
			->orderBy('time_in', 'desc')
			->limit(50)
			->get()
			->map(function ($v) {
				return [
					'id' => $v->id,
					'visitor_name' => $v->visitor_name,
					'visitor_contact' => $v->visitor_contact,
					'purpose' => $v->purpose,
					'time_in' => optional($v->time_in)->toISOString(),
					'time_out' => optional($v->time_out)->toISOString(),
					'pass_number' => $v->pass_number,
				];
			});
		return response()->json(['visitors' => $visitors]);
	}
}
