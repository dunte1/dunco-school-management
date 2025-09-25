<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeeController extends Controller
{
    public function index()
    {
        return view('finance::fees.index');
    }

    public function create()
    {
        return view('finance::fees.create');
    }

    public function store(Request $request)
    {
        // Fee creation logic
        return redirect()->route('finance.fees.index');
    }

    public function show($id)
    {
        return view('finance::fees.show');
    }

    public function edit($id)
    {
        return view('finance::fees.edit');
    }

    public function update(Request $request, $id)
    {
        // Fee update logic
        return redirect()->route('finance.fees.index');
    }

    public function destroy($id)
    {
        // Fee delete logic
        return redirect()->route('finance.fees.index');
    }

    public function collect($id)
    {
        return view('finance::fees.collect');
    }

    public function report()
    {
        return view('finance::fees.report');
    }
}
