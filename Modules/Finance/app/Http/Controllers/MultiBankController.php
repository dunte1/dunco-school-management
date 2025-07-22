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
        $accounts = BankAccount::all();
        return view('finance::banks.index', compact('accounts'));
    }

    public function create()
    {
        return view('finance::banks.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'balance' => 'required|numeric',
        ]);
        BankAccount::create($data);
        return redirect()->route('finance.banks.index')->with('success', 'Bank account created.');
    }

    public function show(BankAccount $bank)
    {
        $bank->load(['outgoingTransfers', 'incomingTransfers']);
        return view('finance::banks.show', compact('bank'));
    }

    public function transfer()
    {
        $accounts = BankAccount::all();
        return view('finance::banks.transfer', compact('accounts'));
    }

    public function storeTransfer(Request $request)
    {
        $data = $request->validate([
            'from_account_id' => 'required|exists:bank_accounts,id',
            'to_account_id' => 'required|exists:bank_accounts,id|different:from_account_id',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'reference' => 'nullable|string',
        ]);
        BankTransfer::create($data);
        // Optionally update balances here
        return redirect()->route('finance.banks.index')->with('success', 'Transfer recorded.');
    }
} 