<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\BankTransaction;
use Modules\Finance\Models\Payment;

class BankReconciliationController extends Controller
{
    public function index()
    {
        $transactions = BankTransaction::with('matchedPayment')->get();
        return view('finance::bank_reconciliation.index', compact('transactions'));
    }

    public function import(Request $request)
    {
        // Placeholder for CSV/Excel import logic
        // You would parse the file and create BankTransaction records
        return back()->with('success', 'Import feature coming soon.');
    }

    public function match(Request $request, BankTransaction $transaction)
    {
        $data = $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);
        $transaction->update([
            'matched_payment_id' => $data['payment_id'],
            'status' => 'reconciled',
        ]);
        return back()->with('success', 'Transaction matched and reconciled.');
    }

    public function updateStatus(Request $request, BankTransaction $transaction)
    {
        $data = $request->validate([
            'status' => 'required|in:unreconciled,reconciled,flagged',
        ]);
        $transaction->update(['status' => $data['status']]);
        return back()->with('success', 'Transaction status updated.');
    }
} 