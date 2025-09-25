<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\FeeType;

class FeeTypeController extends Controller
{
    public function index()
    {
        try {
            if (Schema::hasTable('fee_types')) {
                $types = FeeType::all();
            } else {
                $types = collect();
            }
        } catch (\Exception $e) {
            $types = collect();
        }
        
        return view('finance::fee-types.index', compact('types'));
    }

    public function create()
    {
        return view('finance::fee-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        FeeType::create($request->all());
        return redirect()->route('finance.fee-types.index')->with('success', 'Fee type created successfully.');
    }

    public function show($id)
    {
        return view('finance::fee-types.show');
    }

    public function edit($id)
    {
        return view('finance::fee-types.edit');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $type = FeeType::findOrFail($id);
        $type->update($request->all());
        return redirect()->route('finance.fee-types.index')->with('success', 'Fee type updated successfully.');
    }

    public function destroy($id)
    {
        $type = FeeType::findOrFail($id);
        $type->delete();
        return redirect()->route('finance.fee-types.index')->with('success', 'Fee type deleted successfully.');
    }
}
