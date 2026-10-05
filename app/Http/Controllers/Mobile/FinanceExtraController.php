<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinanceExtraController extends Controller
{
	public function discounts(Request $request)
	{
		return response()->json(['discounts' => []]);
	}

	public function processing(Request $request)
	{
		return response()->json(['processing_fees' => []]);
	}

	public function offlinePayments(Request $request)
	{
		return response()->json(['payments' => []]);
	}

	public function offlineStatus(Request $request)
	{
		return response()->json(['status' => 'pending']);
	}

	public function paymentInstructions(Request $request)
	{
		return response()->json([
			'instructions' => 'Pay via bank or MPESA as per school guidelines.',
			'accounts' => [],
		]);
	}

	public function discountStatus(Request $request)
	{
		return response()->json(['status' => 'none']);
	}
}


