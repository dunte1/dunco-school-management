<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FinanceRoleController extends Controller
{
    public function index()
    {
        return view('finance::roles.index');
    }

    public function create()
    {
        return view('finance::roles.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.roles.index');
    }

    public function show($id)
    {
        return view('finance::roles.show');
    }

    public function edit($id)
    {
        return view('finance::roles.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.roles.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.roles.index');
    }
}
