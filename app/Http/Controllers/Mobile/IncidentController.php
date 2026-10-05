<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hostel\Models\HostelIssue;

class IncidentController extends Controller
{
	public function comments(Request $request)
	{
		$request->validate(['issue_id' => 'required|integer']);
		$issue = HostelIssue::with(['comments' => function ($q) {
			$q->orderBy('created_at');
		}])->find($request->issue_id);
		if (!$issue) return response()->json(['comments' => []]);
		$comments = $issue->comments->map(function ($c) {
			return [
				'id' => $c->id,
				'user_id' => $c->user_id,
				'comment' => $c->comment,
				'created_at' => $c->created_at?->toISOString(),
			];
		});
		return response()->json(['comments' => $comments]);
	}

	public function addComment(Request $request)
	{
		$request->validate([
			'issue_id' => 'required|integer',
			'comment' => 'required|string',
		]);
		$issue = HostelIssue::find($request->issue_id);
		if (!$issue) return response()->json(['message' => 'Issue not found'], 404);
		$comment = $issue->comments()->create([
			'user_id' => $request->user()->id,
			'comment' => $request->comment,
		]);
		return response()->json(['message' => 'Added', 'id' => $comment->id]);
	}

	public function deleteComment(Request $request)
	{
		$request->validate(['id' => 'required|integer']);
		// Soft delete if model supports; otherwise hard delete
		$deleted = \DB::table('hostel_issue_comments')->where('id', (int) $request->id)->delete();
		return response()->json(['deleted' => (bool) $deleted]);
	}
}


