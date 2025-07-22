<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Budget;

class ForecastingController extends Controller
{
    public function index()
    {
        $budgets = Budget::all();
        return view('finance::forecasting.index', compact('budgets'));
    }

    public function create()
    {
        return view('finance::forecasting.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'period' => 'required|string|max:20',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
        ]);
        Budget::create($data);
        return redirect()->route('finance.forecasting.index')->with('success', 'Budget created.');
    }

    public function variance()
    {
        // Placeholder for variance analysis logic
        $data = [];
        return view('finance::forecasting.variance', compact('data'));
    }
} 