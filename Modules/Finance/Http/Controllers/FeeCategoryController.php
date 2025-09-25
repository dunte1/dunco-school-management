<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\FeeCategory;

class FeeCategoryController extends Controller
{
    public function index()
    {
        try {
            if (Schema::hasTable('fee_categories')) {
                $categories = FeeCategory::all();
            } else {
                $categories = collect();
            }
        } catch (\Exception $e) {
            $categories = collect();
        }
        
        return view('finance::fee-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('finance::fee-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        FeeCategory::create($request->all());
        return redirect()->route('finance.fee-categories.index')->with('success', 'Fee category created successfully.');
    }

    public function show($id)
    {
        return view('finance::fee-categories.show');
    }

    public function edit($id)
    {
        return view('finance::fee-categories.edit');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = FeeCategory::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('finance.fee-categories.index')->with('success', 'Fee category updated successfully.');
    }

    public function destroy($id)
    {
        $category = FeeCategory::findOrFail($id);
        $category->delete();
        return redirect()->route('finance.fee-categories.index')->with('success', 'Fee category deleted successfully.');
    }
}
