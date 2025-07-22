<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\FeeType;

class FeeTypeController extends Controller
{
    public function index()
    {
        $types = FeeType::all();
        return view('finance::fee_types.index', compact('types'));
    }

    public function create()
    {
        return view('finance::fee_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        FeeType::create($data);
        return redirect()->route('finance.fee-types.index')->with('success', 'Type created successfully.');
    }

    public function edit(FeeType $feeType)
    {
        return view('finance::fee_types.edit', compact('feeType'));
    }

    public function update(Request $request, FeeType $feeType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $feeType->update($data);
        return redirect()->route('finance.fee-types.index')->with('success', 'Type updated successfully.');
    }

    public function destroy(FeeType $feeType)
    {
        $feeType->delete();
        return redirect()->route('finance.fee-types.index')->with('success', 'Type deleted successfully.');
    }
} 