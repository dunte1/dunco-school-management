<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
	public function list(Request $request)
	{
		$tasks = Cache::get($this->key($request), []);
		return response()->json(['tasks' => array_values($tasks)]);
	}

public function create(Request $request)
	{
		$request->validate([
			'title' => 'required|string',
			'description' => 'nullable|string',
		]);
		$tasks = Cache::get($this->key($request), []);
		$id = count($tasks) + 1;
		$tasks[$id] = [
			'id' => $id,
			'title' => $request->title,
			'description' => $request->description,
			'completed' => false,
			'created_at' => now()->toISOString(),
		];
		Cache::put($this->key($request), $tasks, now()->addDays(3));
		return response()->json(['message' => 'Created', 'id' => $id]);
	}

	public function update(Request $request)
	{
		$request->validate([
			'id' => 'required|integer',
			'title' => 'nullable|string',
			'description' => 'nullable|string',
		]);
		$tasks = Cache::get($this->key($request), []);
		if (!isset($tasks[$request->id])) return response()->json(['message' => 'Not found'], 404);
		$tasks[$request->id] = array_merge($tasks[$request->id], array_filter([
			'title' => $request->title,
			'description' => $request->description,
			'updated_at' => now()->toISOString(),
		]));
		Cache::put($this->key($request), $tasks, now()->addDays(3));
		return response()->json(['message' => 'Updated']);
	}

	public function delete(Request $request)
	{
		$request->validate(['id' => 'required|integer']);
		$tasks = Cache::get($this->key($request), []);
		unset($tasks[$request->id]);
		Cache::put($this->key($request), $tasks, now()->addDays(3));
		return response()->json(['deleted' => true]);
	}

	public function complete(Request $request)
	{
		$request->validate(['id' => 'required|integer']);
		$tasks = Cache::get($this->key($request), []);
		if (!isset($tasks[$request->id])) return response()->json(['message' => 'Not found'], 404);
		$tasks[$request->id]['completed'] = true;
		$tasks[$request->id]['completed_at'] = now()->toISOString();
		Cache::put($this->key($request), $tasks, now()->addDays(3));
		return response()->json(['message' => 'Completed']);
	}

	private function key(Request $request): string
	{
		return 'tasks_' . $request->user()->id;
	}
}


