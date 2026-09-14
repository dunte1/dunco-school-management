<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Fee;

class FeeController extends Controller
{
    public function index()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('fees')) {
                $fees = Fee::with(['category', 'type'])->get();
            } else {
                $fees = collect();
            }
        } catch (\Exception $e) {
            $fees = collect();
        }
        
        return view('finance::fees.index', compact('fees'));
    }

    public function create()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('fee_categories') && \Illuminate\Support\Facades\Schema::hasTable('fee_types')) {
                $categories = \Modules\Finance\Models\FeeCategory::all();
                $types = \Modules\Finance\Models\FeeType::all();
            } else {
                $categories = collect();
                $types = collect();
            }
        } catch (\Exception $e) {
            $categories = collect();
            $types = collect();
        }
        
        return view('finance::fees.create', compact('categories', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'fee_category_id' => 'nullable|exists:fee_categories,id',
            'fee_type_id' => 'nullable|exists:fee_types,id',
        ]);

        Fee::create($request->validated());
        return redirect()->route('finance.fees.index')->with('success', 'Fee created successfully.');
    }

    public function show($id)
    {
        $fee = Fee::with(['category', 'type'])->findOrFail($id);
        return view('finance::fees.show', compact('fee'));
    }

    public function edit($id)
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('fees') && \Illuminate\Support\Facades\Schema::hasTable('fee_categories') && \Illuminate\Support\Facades\Schema::hasTable('fee_types')) {
                $fee = Fee::findOrFail($id);
                $categories = \Modules\Finance\Models\FeeCategory::all();
                $types = \Modules\Finance\Models\FeeType::all();
            } else {
                return redirect()->route('finance.fees.index')->with('error', 'Database tables not available.');
            }
        } catch (\Exception $e) {
            return redirect()->route('finance.fees.index')->with('error', 'Error loading fee data.');
        }
        
        return view('finance::fees.edit', compact('fee', 'categories', 'types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'fee_category_id' => 'nullable|exists:fee_categories,id',
            'fee_type_id' => 'nullable|exists:fee_types,id',
        ]);

        $fee = Fee::findOrFail($id);
        $fee->update($request->validated());
        return redirect()->route('finance.fees.index')->with('success', 'Fee updated successfully.');
    }

    public function destroy($id)
    {
        $fee = Fee::findOrFail($id);
        $fee->delete();
        return redirect()->route('finance.fees.index')->with('success', 'Fee deleted successfully.');
    }

    public function collect($id)
    {
        return view('finance::fees.collect');
    }

    public function report()
    {
        return view('finance::fees.report');
    }
}
