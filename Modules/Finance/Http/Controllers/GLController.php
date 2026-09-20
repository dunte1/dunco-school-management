<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Models\LedgerEntry;

class GLController extends Controller
{
    public function index(Request $request)
    {
        $query = LedgerEntry::query();

        if ($request->filled('account')) {
            $query->where('account', $request->account);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
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
                    ->orWhere('reference', 'like', '%'.$request->search.'%')
                    ->orWhere('account', 'like', '%'.$request->search.'%');
            });
        }

        $entries = $query->orderByDesc('date')->paginate(50);

        $accounts = LedgerEntry::distinct()->pluck('account')->sort();

        $summary = [
            'total_debit' => (float) LedgerEntry::sum('debit'),
            'total_credit' => (float) LedgerEntry::sum('credit'),
            'net_balance' => (float) LedgerEntry::sum(DB::raw('debit - credit')),
            'entry_count' => LedgerEntry::count(),
        ];

        $accountBalances = LedgerEntry::select('account', DB::raw('SUM(debit) - SUM(credit) as balance'))
            ->groupBy('account')
            ->orderBy('account')
            ->get();

        return view('finance::gl.index', compact('entries', 'accounts', 'summary', 'accountBalances'));
    }

    public function create()
    {
        $accounts = LedgerEntry::distinct()->pluck('account')->sort();

        return view('finance::gl.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'account' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'description' => 'required|string|max:500',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:100',
            'related_id' => 'nullable|integer',
            'related_type' => 'nullable|string|max:255',
        ]);

        $debit = (float) ($data['debit'] ?? 0);
        $credit = (float) ($data['credit'] ?? 0);

        if ($debit === 0 && $credit === 0) {
            return back()->withErrors(['debit' => 'Either debit or credit amount must be greater than zero.'])->withInput();
        }

        if ($debit > 0 && $credit > 0) {
            return back()->withErrors(['debit' => 'Entry cannot have both debit and credit amounts.'])->withInput();
        }

        $entry = LedgerEntry::create([
            'date' => $data['date'],
            'account' => $data['account'],
            'type' => $data['type'],
            'description' => $data['description'],
            'debit' => $debit,
            'credit' => $credit,
            'reference' => $data['reference'] ?? null,
            'related_id' => $data['related_id'] ?? null,
            'related_type' => $data['related_type'] ?? null,
        ]);

        return redirect()->route('finance.gl.show', $entry->id)
            ->with('success', 'Ledger entry created successfully.');
    }

    public function show($id)
    {
        $entry = LedgerEntry::findOrFail($id);
        $relatedEntries = LedgerEntry::where('id', '!=', $id)
            ->where(function ($q) use ($entry) {
                $q->where('reference', $entry->reference)
                    ->orWhere(function ($q2) use ($entry) {
                        $q2->where('related_id', $entry->related_id)
                            ->where('related_type', $entry->related_type)
                            ->whereNotNull($entry->related_id);
                    });
            })
            ->get();

        return view('finance::gl.show', compact('entry', 'relatedEntries'));
    }

    public function edit($id)
    {
        $entry = LedgerEntry::findOrFail($id);
        $accounts = LedgerEntry::distinct()->pluck('account')->sort();

        return view('finance::gl.edit', compact('entry', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $entry = LedgerEntry::findOrFail($id);

        $data = $request->validate([
            'date' => 'required|date',
            'account' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'description' => 'required|string|max:500',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:100',
        ]);

        $debit = (float) ($data['debit'] ?? 0);
        $credit = (float) ($data['credit'] ?? 0);

        if ($debit === 0 && $credit === 0) {
            return back()->withErrors(['debit' => 'Either debit or credit amount must be greater than zero.'])->withInput();
        }

        if ($debit > 0 && $credit > 0) {
            return back()->withErrors(['debit' => 'Entry cannot have both debit and credit amounts.'])->withInput();
        }

        $entry->update([
            'date' => $data['date'],
            'account' => $data['account'],
            'type' => $data['type'],
            'description' => $data['description'],
            'debit' => $debit,
            'credit' => $credit,
            'reference' => $data['reference'] ?? null,
        ]);

        return redirect()->route('finance.gl.show', $entry->id)
            ->with('success', 'Ledger entry updated successfully.');
    }

    public function destroy($id)
    {
        $entry = LedgerEntry::findOrFail($id);
        $entry->delete();

        return redirect()->route('finance.gl.index')
            ->with('success', 'Ledger entry deleted successfully.');
    }
}
