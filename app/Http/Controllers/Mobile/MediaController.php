<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
	public function downloads(Request $request)
	{
		return response()->json(['downloads' => []]);
	}

	public function downloadsById(Request $request)
	{
		$request->validate(['id' => 'required|integer']);
		return response()->json(['downloads' => []]);
	}

	public function videos(Request $request)
	{
		return response()->json(['videos' => []]);
	}
}


