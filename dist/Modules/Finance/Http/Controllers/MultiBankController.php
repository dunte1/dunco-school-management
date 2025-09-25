<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MultiBankController extends Controller
{
    public function index()
    {
        return view('finance::multi-bank.index');
    }

    public function create()
    {
        return view('finance::multi-bank.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.multi-bank.index');
    }

    public function show($id)
    {
        return view('finance::multi-bank.show');
    }

    public function edit($id)
    {
        return view('finance::multi-bank.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.multi-bank.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.multi-bank.index');
    }
}
