<?php

namespace Modules\API\Http\Controllers\Mobile;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class FinanceController extends Controller
{
    public function getFees(): JsonResponse
    {
        $fees = \Modules\Finance\Models\Fee::with(['category', 'type'])->orderBy('name')->get();
        return response()->json(['success' => true, 'data' => $fees]);
    }

    public function getFee($id): JsonResponse
    {
        $fee = \Modules\Finance\Models\Fee::with(['category', 'type'])->find($id);
        if (!$fee) return response()->json(['message' => 'Fee not found'], 404);
        return response()->json(['success' => true, 'data' => $fee]);
    }

    public function getStudentFees(): JsonResponse
    {
        $fees = \Modules\Academic\Models\StudentFee::with('fee')
            ->where('student_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(25);
        return response()->json(['success' => true, 'data' => $fees]);
    }

    public function getPayments(): JsonResponse
    {
        $payments = \Modules\Finance\Models\Payment::orderByDesc('created_at')->paginate(25);
        return response()->json(['success' => true, 'data' => $payments]);
    }

    public function getInvoice($id): JsonResponse
    {
        $invoice = \Modules\Finance\Models\Invoice::find($id);
        if (!$invoice) return response()->json(['message' => 'Invoice not found'], 404);
        return response()->json(['success' => true, 'data' => $invoice]);
    }
}
