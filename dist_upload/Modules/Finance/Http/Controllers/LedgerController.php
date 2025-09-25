<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LedgerController extends Controller
{
    public function index()
    {
        return view('finance::ledger.index');
    }

    public function create()
    {
        return view('finance::ledger.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.ledger.index');
    }

    public function show($id)
    {
        return view('finance::ledger.show');
    }

    public function edit($id)
    {
        return view('finance::ledger.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.ledger.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.ledger.index');
    }
}
