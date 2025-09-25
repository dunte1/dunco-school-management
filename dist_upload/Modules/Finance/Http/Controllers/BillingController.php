<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BillingController extends Controller
{
    public function index()
    {
        return view('finance::billing.index');
    }

    public function create()
    {
        return view('finance::billing.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.billing.index');
    }

    public function show($id)
    {
        return view('finance::billing.show');
    }

    public function edit($id)
    {
        return view('finance::billing.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.billing.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.billing.index');
    }
}
