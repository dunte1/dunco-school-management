<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\BankTransaction;
use Modules\Finance\Models\BankAccount;
use Modules\Finance\Models\LedgerEntry;
use Modules\Finance\Models\Payment;

class BankReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $query = BankTransaction::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%'.$request->search.'%')
                    ->orWhere('reference', 'like', '%'.$request->search.'%');
            });
        }

        $transactions = $query->orderByDesc('date')->paginate(50);

        $summary = [
            'total_transactions' => (float) BankTransaction::count(),
            'matched' => (float) BankTransaction::where('status', 'matched')->count(),
            'unmatched' => (float) BankTransaction::where('status', 'unmatched')->count(),
            'disputed' => (float) BankTransaction::where('status', 'disputed')->count(),
            'total_deposits' => (float) BankTransaction::where('amount', '>', 0)->sum('amount'),
            'total_withdrawals' => (float) BankTransaction::where('amount', '<', 0)->sum(DB::raw('ABS(amount)')),
            'unmatched_amount' => (float) BankTransaction::where('status', 'unmatched')->sum('amount'),
        ];

        return view('finance::bank_reconciliation.index', compact('transactions', 'summary'));
    }

    public function create()
    {
        return view('finance::bank_reconciliation.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:100',
            'type' => 'required|in:deposit,withdrawal,transfer,fee,interest',
        ]);

        $amount = (float) $data['amount'];
        if ($data['type'] === 'withdrawal' || $data['type'] === 'fee') {
            $amount = -abs($amount);
        }

        $transaction = BankTransaction::create([
            'date' => $data['date'],
            'amount' => $amount,
            'description' => $data['description'] ?? null,
            'reference' => $data['reference'] ?? null,
            'status' => 'unmatched',
        ]);

        $this->createLedgerEntry($transaction, $data['type']);

        return redirect()->route('finance.bank-reconciliation.index')
            ->with('success', 'Bank transaction recorded successfully.');
    }

    public function show($id)
    {
        $transaction = BankTransaction::findOrFail($id);
        $relatedEntries = LedgerEntry::where('reference', $transaction->reference)
            ->orWhere('related_id', $transaction->id)
            ->get();

        return view('finance::bank_reconciliation.show', compact('transaction', 'relatedEntries'));
    }

    public function edit($id)
    {
        $transaction = BankTransaction::findOrFail($id);

        return view('finance::bank_reconciliation.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $transaction = BankTransaction::findOrFail($id);

        $data = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:100',
            'status' => 'required|in:unmatched,matched,disputed,reconciled',
        ]);

        $transaction->update($data);

        return redirect()->route('finance.bank-reconciliation.index')
            ->with('success', 'Bank transaction updated successfully.');
    }

    public function destroy($id)
    {
        $transaction = BankTransaction::findOrFail($id);

        if ($transaction->status === 'reconciled') {
            return back()->with('error', 'Cannot delete a reconciled transaction.');
        }

        LedgerEntry::where('reference', $transaction->reference)
            ->orWhere('related_id', $transaction->id)
            ->delete();

        $transaction->delete();

        return redirect()->route('finance.bank-reconciliation.index')
            ->with('success', 'Bank transaction deleted successfully.');
    }

    public function reconcile(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'transaction_ids' => 'required|array',
                'transaction_ids.*' => 'exists:bank_transactions,id',
            ]);

            $count = 0;
            foreach ($data['transaction_ids'] as $transactionId) {
                $transaction = BankTransaction::findOrFail($transactionId);
                if ($transaction->status !== 'reconciled') {
                    $transaction->update(['status' => 'reconciled']);
                    $count++;
                }
            }

            return back()->with('success', "{$count} transactions reconciled successfully.");
        }

        $unmatched = BankTransaction::where('status', 'unmatched')
            ->orderByDesc('date')
            ->get();

        $ledgerUnmatched = LedgerEntry::whereNotIn('id', function ($query) {
            $query->select('related_id')
                ->from('bank_transactions')
                ->whereNotNull('related_id')
                ->where('related_type', LedgerEntry::class);
        })->orderByDesc('date')->get();

        return view('finance::bank_reconciliation.reconcile', compact('unmatched', 'ledgerUnmatched'));
    }

    public function report(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $transactions = BankTransaction::whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->orderBy('date')
            ->get();

        $summary = [
            'period_start' => $from,
            'period_end' => $to,
            'opening_balance' => (float) BankTransaction::whereDate('date', '<', $from)->sum('amount'),
            'total_deposits' => (float) $transactions->where('amount', '>', 0)->sum('amount'),
            'total_withdrawals' => (float) $transactions->where('amount', '<', 0)->sum(fn ($t) => abs($t->amount)),
            'closing_balance' => (float) BankTransaction::whereDate('date', '<=', $to)->sum('amount'),
            'matched_count' => $transactions->where('status', 'matched')->count(),
            'unmatched_count' => $transactions->where('status', 'unmatched')->count(),
            'disputed_count' => $transactions->where('status', 'disputed')->count(),
        ];

        $byStatus = $transactions->groupBy('status')->map(fn ($group) => [
            'count' => $group->count(),
            'total' => (float) $group->sum('amount'),
        ]);

        return view('finance::bank_reconciliation.report', compact('transactions', 'summary', 'byStatus', 'from', 'to'));
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
        $txn = BankTransaction::findOrFail($transaction);

        $request->validate([
            'ledger_entry_id' => 'nullable|exists:ledger_entries,id',
        ]);

        $txn->update(['status' => 'matched']);

        if ($request->filled('ledger_entry_id')) {
            $txn->update([
                'matched_payment_id' => null,
            ]);

            LedgerEntry::where('id', $request->ledger_entry_id)->update([
                'related_id' => $txn->id,
                'related_type' => BankTransaction::class,
            ]);
        }

        return back()->with('success', 'Transaction matched successfully.');
    }

    public function updateStatus(Request $request, $transaction)
    {
        $data = $request->validate([
            'status' => 'required|string|in:unmatched,matched,disputed,reconciled',
        ]);

        BankTransaction::findOrFail($transaction)->update($data);

        return back()->with('success', 'Transaction status updated.');
    }

    protected function createLedgerEntry(BankTransaction $transaction, string $type): void
    {
        $account = match ($type) {
            'deposit' => 'Bank - Deposits',
            'withdrawal' => 'Bank - Withdrawals',
            'transfer' => 'Bank - Transfers',
            'fee' => 'Bank Charges',
            'interest' => 'Interest Income',
            default => 'Bank',
        };

        LedgerEntry::create([
            'date' => $transaction->date,
            'account' => $account,
            'type' => 'asset',
            'description' => $transaction->description ?? "Bank transaction #{$transaction->id}",
            'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
            'credit' => $transaction->amount < 0 ? abs($transaction->amount) : 0,
            'reference' => $transaction->reference,
            'related_id' => $transaction->id,
            'related_type' => BankTransaction::class,
        ]);
    }
}
