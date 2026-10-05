<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TimelineController extends Controller
{
	public function list(Request $request)
	{
		$userId = $request->user()->id;
		$items = $this->fetchTimeline($userId);
		return response()->json(['timeline' => $items]);
	}

	public function save(Request $request)
	{
		$request->validate([
			'id' => 'nullable|integer',
			'title' => 'required|string',
			'description' => 'nullable|string',
			'date' => 'nullable|date',
		]);
		$userId = $request->user()->id;
		$item = [
			'user_id' => $userId,
			'title' => $request->title,
			'description' => $request->description,
			'date' => $request->date ?: now()->toDateString(),
			'created_at' => now(),
			'updated_at' => now(),
		];
		if ($this->timelineTableExists()) {
			if ($request->id) {
				DB::table('user_timeline')->where('id', $request->id)->where('user_id', $userId)->update($item);
				$targetId = (int) $request->id;
			} else {
				$targetId = DB::table('user_timeline')->insertGetId($item);
			}
		} else {
			// Fallback to cache
			$key = $this->cacheKey($userId);
			$list = Cache::get($key, []);
			if ($request->id) {
				foreach ($list as &$row) {
					if ($row['id'] == $request->id) {
						$row = array_merge($row, $item);
						break;
					}
				}
				$targetId = (int) $request->id;
			} else {
				$targetId = count($list) + 1;
				$list[] = array_merge(['id' => $targetId], $item);
			}
			Cache::put($key, $list, now()->addDays(2));
		}
		return response()->json(['message' => 'Saved', 'id' => $targetId]);
	}

	public function delete(Request $request)
	{
		$request->validate(['id' => 'required|integer']);
		$userId = $request->user()->id;
		$deleted = false;
		if ($this->timelineTableExists()) {
			$deleted = DB::table('user_timeline')->where('id', $request->id)->where('user_id', $userId)->delete() > 0;
		} else {
			$key = $this->cacheKey($userId);
			$list = Cache::get($key, []);
			$list = array_values(array_filter($list, fn($row) => $row['id'] != $request->id));
			Cache::put($key, $list, now()->addDays(2));
			$deleted = true;
		}
		return response()->json(['deleted' => $deleted]);
	}

	private function timelineTableExists(): bool
	{
		try {
			return DB::getSchemaBuilder()->hasTable('user_timeline');
		} catch (\Throwable $e) {
			return false;
		}
	}

	private function fetchTimeline(int $userId): array
	{
		if ($this->timelineTableExists()) {
			return DB::table('user_timeline')->where('user_id', $userId)->orderBy('date', 'desc')->limit(100)->get()->map(function ($r) {
				return [
					'id' => $r->id,
					'title' => $r->title,
					'description' => $r->description,
					'date' => (string) $r->date,
					'created_at' => (string) $r->created_at,
				];
			})->toArray();
		}
		return Cache::get($this->cacheKey($userId), []);
	}

	private function cacheKey(int $userId): string
	{
		return 'timeline_' . $userId;
	}
}
