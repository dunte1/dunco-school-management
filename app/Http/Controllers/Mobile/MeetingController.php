<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\OnlineClass;

class MeetingController extends Controller
{
	public function live(Request $request)
	{
		$classes = OnlineClass::orderBy('start_time', 'desc')->limit(50)->get()->map(function ($c) {
			return [
				'id' => $c->id,
				'platform' => $c->platform ?? 'zoom',
				'title' => $c->title ?? 'Live Class',
				'join_url' => $c->join_url ?? null,
				'start_time' => optional($c->start_time)->toISOString(),
				'end_time' => optional($c->end_time)->toISOString(),
			];
		});
		return response()->json(['classes' => $classes]);
	}

	public function history(Request $request)
	{
		return response()->json(['history' => []]);
	}

	public function gmeet(Request $request)
	{
		return $this->live($request);
	}

	public function gmeetSettings(Request $request)
	{
		return response()->json(['settings' => ['enabled' => true]]);
	}

	public function zoomSettings(Request $request)
	{
		return response()->json(['settings' => ['enabled' => true]]);
	}
}


