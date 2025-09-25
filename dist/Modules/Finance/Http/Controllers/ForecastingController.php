<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ForecastingController extends Controller
{
    public function index()
    {
        return view('finance::forecasting.index');
    }

    public function create()
    {
        return view('finance::forecasting.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.forecasting.index');
    }

    public function show($id)
    {
        return view('finance::forecasting.show');
    }

    public function edit($id)
    {
        return view('finance::forecasting.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.forecasting.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.forecasting.index');
    }
}
