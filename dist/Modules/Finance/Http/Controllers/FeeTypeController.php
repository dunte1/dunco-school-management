<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeeTypeController extends Controller
{
    public function index()
    {
        return view('finance::fee-types.index');
    }

    public function create()
    {
        return view('finance::fee-types.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.finance.fee-types.index');
    }

    public function show($id)
    {
        return view('finance::fee-types.show');
    }

    public function edit($id)
    {
        return view('finance::fee-types.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.finance.fee-types.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.finance.fee-types.index');
    }
}
