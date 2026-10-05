<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemCompatController extends Controller
{
	public function maintenance(Request $request)
	{
		return response()->json([
			'maintenance_mode' => app()->isDownForMaintenance() ? '1' : '0',
		]);
	}

	public function lockStudentPanel(Request $request)
	{
		return response()->json(['locked' => false]);
	}

	public function privacyPolicy()
	{
		return response()->json([
			'content' => 'Privacy policy content will be provided by the school.',
			'url' => url('/privacy-policy'),
		]);
	}

	public function schoolDetails(Request $request)
	{
		return response()->json([
			'schools' => [[
				'id' => 1,
				'name' => config('app.name'),
				'address' => config('app.url'),
			]]
		]);
	}
}


