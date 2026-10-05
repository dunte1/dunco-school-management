<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Tax;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::paginate(15);
        return view('hr::tax.index', compact('taxes'));
    }

    public function create()
    {
        return view('hr::tax.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rate' => 'required|numeric|min:0|max:100',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        Tax::create($request->all());

        return redirect()->route('hr.tax.index')
                        ->with('success', 'Tax created successfully.');
    }

    public function show($id)
    {
        $tax = Tax::findOrFail($id);
        return view('hr::tax.show', compact('tax'));
    }

    public function edit($id)
    {
        $tax = Tax::findOrFail($id);
        return view('hr::tax.edit', compact('tax'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rate' => 'required|numeric|min:0|max:100',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $tax = Tax::findOrFail($id);
        $tax->update($request->all());

        return redirect()->route('hr.tax.index')
                        ->with('success', 'Tax updated successfully.');
    }

    public function destroy($id)
    {
        $tax = Tax::findOrFail($id);
        $tax->delete();

        return redirect()->route('hr.tax.index')
                        ->with('success', 'Tax deleted successfully.');
    }
}
