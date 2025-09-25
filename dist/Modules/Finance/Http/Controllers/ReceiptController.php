<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReceiptController extends Controller
{
    public function index()
    {
        return view('finance::receipts.index');
    }

    public function create()
    {
        return view('finance::receipts.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.receipts.index');
    }

    public function show($id)
    {
        return view('finance::receipts.show');
    }

    public function edit($id)
    {
        return view('finance::receipts.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.receipts.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.receipts.index');
    }
}
