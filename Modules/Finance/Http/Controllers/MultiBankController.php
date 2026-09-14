<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\BankAccount;
use Modules\Finance\Models\BankTransfer;

class MultiBankController extends Controller
{
    public function index()
    {
        try {
            $accounts = \Illuminate\Support\Facades\Schema::hasTable('bank_accounts')
                ? BankAccount::all()
                : collect();
        } catch (\Exception $e) {
            $accounts = collect();
        }

        return view('finance::banks.index', compact('accounts'));
    }

    public function create()
    {
        return view('finance::banks.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric',
        ]);

        BankAccount::create($data);

        return redirect()->route('finance.banks.index')->with('success', 'Bank account created.');
    }

    public function show($id)
    {
        $account = BankAccount::findOrFail($id);

        return view('finance::banks.show', compact('account'));
    }

    public function edit($id)
    {
        $account = BankAccount::findOrFail($id);

        return view('finance::banks.edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $account = BankAccount::findOrFail($id);
        $account->update($request->validate([
            'name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric',
        ]));

        return redirect()->route('finance.banks.index')->with('success', 'Bank account updated.');
    }

    public function destroy($id)
    {
        BankAccount::findOrFail($id)->delete();

        return redirect()->route('finance.banks.index')->with('success', 'Bank account deleted.');
    }

    public function transfer()
    {
        $accounts = BankAccount::all();

        return view('finance::banks.transfer', ['accounts' => $accounts, 'account' => null]);
    }

    public function storeTransfer(Request $request)
    {
        $data = $request->validate([
            'from_account_id' => 'required|exists:bank_accounts,id',
            'to_account_id' => 'required|exists:bank_accounts,id|different:from_account_id',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ]);

        $data['transfer_date'] = $data['transfer_date'] ?? now()->toDateString();

        BankTransfer::create($data);

        return redirect()->route('finance.banks.index')->with('success', 'Bank transfer recorded.');
    }
}
