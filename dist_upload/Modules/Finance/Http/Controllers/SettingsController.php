<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return view('finance::settings.index');
    }

    public function create()
    {
        return view('finance::settings.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.settings.index');
    }

    public function show($id)
    {
        return view('finance::settings.show');
    }

    public function edit($id)
    {
        return view('finance::settings.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.settings.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.settings.index');
    }
}
