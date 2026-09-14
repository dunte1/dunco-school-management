<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\BankTransaction;

class BankReconciliationController extends Controller
{
    public function index()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('bank_transactions')) {
                $transactions = BankTransaction::all();
            } else {
                $transactions = collect();
            }
        } catch (\Exception $e) {
            $transactions = collect();
        }
        
        return view('finance::bank_reconciliation.index', compact('transactions'));
    }

    public function create()
    {
        return view('finance::bank_reconciliation.create');
    }

    public function store(Request $request)
    {
        // Bank reconciliation creation logic
        return redirect()->route('finance.bank-reconciliation.index');
    }

    public function show($id)
    {
        return view('finance::bank_reconciliation.show');
    }

    public function edit($id)
    {
        return view('finance::bank_reconciliation.edit');
    }

    public function update(Request $request, $id)
    {
        // Bank reconciliation update logic
        return redirect()->route('finance.bank-reconciliation.index');
    }

    public function destroy($id)
    {
        // Bank reconciliation delete logic
        return redirect()->route('finance.bank-reconciliation.index');
    }

    public function reconcile($id)
    {
        return view('finance::bank_reconciliation.reconcile');
    }

    public function report()
    {
        return view('finance::bank_reconciliation.report');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');

        $count = 0;
        if ($handle !== false) {
            fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                BankTransaction::create([
                    'date' => $row[0] ?? now()->toDateString(),
                    'amount' => is_numeric($row[1] ?? null) ? $row[1] : 0,
                    'description' => $row[2] ?? null,
                    'reference' => $row[3] ?? null,
                    'status' => 'unmatched',
                ]);
                $count++;
            }
            fclose($handle);
        }

        return back()->with('success', $count.' bank transactions imported.');
    }

    public function match(Request $request, $transaction)
    {
        BankTransaction::findOrFail($transaction)->update(['status' => 'matched']);

        return back()->with('success', 'Transaction matched.');
    }

    public function updateStatus(Request $request, $transaction)
    {
        $data = $request->validate([
            'status' => 'required|string|max:50',
        ]);

        BankTransaction::findOrFail($transaction)->update($data);

        return back()->with('success', 'Transaction status updated.');
    }
}
