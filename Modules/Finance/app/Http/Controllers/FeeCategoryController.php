<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\FeeCategory;

class FeeCategoryController extends Controller
{
    public function index()
    {
        $categories = FeeCategory::all();
        return view('finance::fee_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('finance::fee_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        FeeCategory::create($data);
        return redirect()->route('finance.fee-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(FeeCategory $feeCategory)
    {
        return view('finance::fee_categories.edit', compact('feeCategory'));
    }

    public function update(Request $request, FeeCategory $feeCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $feeCategory->update($data);
        return redirect()->route('finance.fee-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(FeeCategory $feeCategory)
    {
        $feeCategory->delete();
        return redirect()->route('finance.fee-categories.index')->with('success', 'Category deleted successfully.');
    }
} 