<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Tax;

class TaxController extends Controller
{
    public function index()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('taxes')) {
                $taxes = Tax::all();
            } else {
                $taxes = collect();
            }
        } catch (\Exception $e) {
            $taxes = collect();
        }
        
        return view('finance::taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('finance::taxes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'type' => 'required|string|in:percentage,fixed',
            'active' => 'boolean',
        ]);

        Tax::create($request->validated());
        return redirect()->route('finance.taxes.index')->with('success', 'Tax rule created successfully.');
    }

    public function show($id)
    {
        return view('finance::taxes.show');
    }

    public function edit($id)
    {
        return view('finance::taxes.edit');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'type' => 'required|string|in:percentage,fixed',
            'active' => 'boolean',
        ]);

        $tax = Tax::findOrFail($id);
        $tax->update($request->validated());
        return redirect()->route('finance.taxes.index')->with('success', 'Tax rule updated successfully.');
    }

    public function destroy($id)
    {
        $tax = Tax::findOrFail($id);
        $tax->delete();
        return redirect()->route('finance.taxes.index')->with('success', 'Tax rule deleted successfully.');
    }
}
