<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentController extends Controller
{
    public function index()
    {
        return view('finance::payments.index');
    }

    public function create()
    {
        return view('finance::payments.create');
    }

    public function store(Request $request)
    {
        // Payment creation logic
        return redirect()->route('finance.payments.index');
    }

    public function show($id)
    {
        return view('finance::payments.show');
    }

    public function edit($id)
    {
        return view('finance::payments.edit');
    }

    public function update(Request $request, $id)
    {
        // Payment update logic
        return redirect()->route('finance.payments.index');
    }

    public function destroy($id)
    {
        // Payment delete logic
        return redirect()->route('finance.payments.index');
    }

    public function receipt($id)
    {
        return view('finance::payments.receipt');
    }

    public function report()
    {
        return view('finance::payments.report');
    }
}
