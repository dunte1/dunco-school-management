<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Payment;
use PDF;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('invoice');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('invoice_id', $search)
                  ->orWhere('method', 'like', "%$search%");
            });
        }
        if ($request->filled('from')) {
            $query->whereDate('payment_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('payment_date', '<=', $request->to);
        }
        $receipts = $query->orderByDesc('payment_date')->get();
        return view('finance::receipts.index', compact('receipts'));
    }

    public function show(Payment $receipt)
    {
        $receipt->load('invoice');
        return view('finance::receipts.show', compact('receipt'));
    }

    public function print(Payment $receipt)
    {
        $receipt->load('invoice');
        return view('finance::receipts.print', compact('receipt'));
    }

    public function download(Payment $receipt)
    {
        $receipt->load('invoice');
        $pdf = PDF::loadView('finance::receipts.print', compact('receipt'));
        return $pdf->download('receipt_'.$receipt->id.'.pdf');
    }
} 