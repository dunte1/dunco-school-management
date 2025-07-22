<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Fee;
use Modules\Finance\Models\FeeCategory;
use Modules\Finance\Models\FeeType;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::with(['category', 'type'])->get();
        return view('finance::fees.index', compact('fees'));
    }

    public function create()
    {
        $categories = FeeCategory::all();
        $types = FeeType::all();
        return view('finance::fees.create', compact('categories', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'fee_category_id' => 'nullable|exists:fee_categories,id',
            'fee_type_id' => 'nullable|exists:fee_types,id',
        ]);
        Fee::create($data);
        return redirect()->route('finance.fees.index')->with('success', 'Fee created successfully.');
    }

    public function edit(Fee $fee)
    {
        $categories = FeeCategory::all();
        $types = FeeType::all();
        return view('finance::fees.edit', compact('fee', 'categories', 'types'));
    }

    public function update(Request $request, Fee $fee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'fee_category_id' => 'nullable|exists:fee_categories,id',
            'fee_type_id' => 'nullable|exists:fee_types,id',
        ]);
        $fee->update($data);
        return redirect()->route('finance.fees.index')->with('success', 'Fee updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();
        return redirect()->route('finance.fees.index')->with('success', 'Fee deleted successfully.');
    }
} 