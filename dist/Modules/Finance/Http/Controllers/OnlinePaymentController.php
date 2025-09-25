<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OnlinePaymentController extends Controller
{
    public function index()
    {
        return view('finance::online-payment.index');
    }

    public function create()
    {
        return view('finance::online-payment.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.online-payment.index');
    }

    public function show($id)
    {
        return view('finance::online-payment.show');
    }

    public function edit($id)
    {
        return view('finance::online-payment.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.online-payment.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.online-payment.index');
    }
}
