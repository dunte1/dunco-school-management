<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
	public function getLanguage(Request $request)
	{
		$lang = session('mobile_lang', config('app.locale'));
		return response()->json(['language' => $lang]);
	}

	public function setLanguage(Request $request)
	{
		$request->validate(['language' => 'required|string']);
		session(['mobile_lang' => $request->language]);
		app()->setLocale($request->language);
		return response()->json(['language' => $request->language]);
	}

	public function getCurrency(Request $request)
	{
		return response()->json([
			'currency' => [
				'symbol' => config('app.currency_symbol', 'KSh'),
				'code' => config('app.currency_code', 'KES'),
				'base_price' => '1.00',
			]
		]);
	}

	public function setCurrency(Request $request)
	{
		$request->validate(['code' => 'required|string']);
		session(['mobile_currency_code' => $request->code]);
		return response()->json(['currency' => ['code' => $request->code]]);
	}
}


