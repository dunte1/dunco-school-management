<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Tax;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::all();
        return view('finance::taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('finance::taxes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric',
            'type' => 'required|in:percentage,fixed',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);
        Tax::create($data);
        return redirect()->route('finance.taxes.index')->with('success', 'Tax rule created.');
    }

    public function edit(Tax $tax)
    {
        return view('finance::taxes.edit', compact('tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric',
            'type' => 'required|in:percentage,fixed',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);
        $tax->update($data);
        return redirect()->route('finance.taxes.index')->with('success', 'Tax rule updated.');
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();
        return redirect()->route('finance.taxes.index')->with('success', 'Tax rule deleted.');
    }
} 