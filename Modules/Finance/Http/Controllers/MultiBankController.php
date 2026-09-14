<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\BankAccount;

class MultiBankController extends Controller
{
    public function index()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('bank_accounts')) {
                $accounts = BankAccount::all();
            } else {
                $accounts = collect();
            }
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
        return redirect()->route('finance.multi-bank.index');
    }

    public function show($id)
    {
        return view('finance::banks.show');
    }

    public function edit($id)
    {
        return view('finance::banks.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.multi-bank.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.multi-bank.index');
    }
}
