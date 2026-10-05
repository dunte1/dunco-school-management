<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Communication\Models\MessageThread;
use Modules\Communication\Models\Message;

class ForumController extends Controller
{
	public function list(Request $request)
	{
		$user = $request->user();
		$threads = MessageThread::where('school_id', $user->school_id)
			->orderBy('updated_at', 'desc')
			->limit(50)
			->get()
			->map(function ($t) {
				return [
					'id' => $t->id,
					'subject' => $t->subject ?? 'Discussion',
					'updated_at' => $t->updated_at?->toISOString(),
				];
			});
		return response()->json(['threads' => $threads]);
	}

	public function messages(Request $request)
	{
		$request->validate(['thread_id' => 'required|integer']);
		$messages = Message::where('thread_id', (int) $request->thread_id)
			->orderBy('created_at')
			->get()
			->map(function ($m) {
				return [
					'id' => $m->id,
					'user_id' => $m->user_id,
					'body' => $m->body,
					'created_at' => $m->created_at?->toISOString(),
				];
			});
		return response()->json(['messages' => $messages]);
	}

	public function addMessage(Request $request)
	{
		$request->validate([
			'thread_id' => 'required|integer',
			'body' => 'required|string',
		]);
		$msg = Message::create([
			'thread_id' => (int) $request->thread_id,
			'user_id' => $request->user()->id,
			'body' => $request->body,
		]);
		return response()->json(['message' => 'Added', 'id' => $msg->id]);
	}

	public function deleteMessage(Request $request)
	{
		$request->validate(['id' => 'required|integer']);
		Message::where('id', (int) $request->id)->delete();
		return response()->json(['deleted' => true]);
	}
}
