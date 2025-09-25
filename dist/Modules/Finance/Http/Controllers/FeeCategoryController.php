<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeeCategoryController extends Controller
{
    public function index()
    {
        return view('finance::fee-categories.index');
    }

    public function create()
    {
        return view('finance::fee-categories.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.finance.fee-categories.index');
    }

    public function show($id)
    {
        return view('finance::fee-categories.show');
    }

    public function edit($id)
    {
        return view('finance::fee-categories.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.finance.fee-categories.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.finance.fee-categories.index');
    }
}
