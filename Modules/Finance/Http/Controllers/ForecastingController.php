<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ForecastingController extends Controller
{
    public function index()
    {
        $budgets = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('budgets')) {
                $budgets = \Modules\Finance\Models\Budget::all();
            }
        } catch (\Throwable $e) {
        }

        return view('finance::forecasting.index', compact('budgets'));
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

    public function variance()
    {
        return view('finance::forecasting.variance');
    }
}
