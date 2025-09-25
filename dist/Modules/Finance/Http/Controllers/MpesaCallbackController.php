<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MpesaCallbackController extends Controller
{
    public function index()
    {
        return view('finance::mpesa.index');
    }

    public function create()
    {
        return view('finance::mpesa.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.mpesa.index');
    }

    public function show($id)
    {
        return view('finance::mpesa.show');
    }

    public function edit($id)
    {
        return view('finance::mpesa.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.mpesa.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.mpesa.index');
    }
}
