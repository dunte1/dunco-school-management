<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TaxController extends Controller
{
    public function index()
    {
        return view('finance::tax.index');
    }

    public function create()
    {
        return view('finance::tax.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.tax.index');
    }

    public function show($id)
    {
        return view('finance::tax.show');
    }

    public function edit($id)
    {
        return view('finance::tax.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.tax.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.tax.index');
    }
}
