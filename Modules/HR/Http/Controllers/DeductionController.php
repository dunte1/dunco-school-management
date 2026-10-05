<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Deduction;

class DeductionController extends Controller
{
    public function index()
    {
        $deductions = Deduction::paginate(15);
        return view('hr::deductions.index', compact('deductions'));
    }

    public function create()
    {
        return view('hr::deductions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'amount' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        Deduction::create($request->all());

        return redirect()->route('hr.deductions.index')
                        ->with('success', 'Deduction created successfully.');
    }

    public function show($id)
    {
        $deduction = Deduction::findOrFail($id);
        return view('hr::deductions.show', compact('deduction'));
    }

    public function edit($id)
    {
        $deduction = Deduction::findOrFail($id);
        return view('hr::deductions.edit', compact('deduction'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'amount' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        $deduction = Deduction::findOrFail($id);
        $deduction->update($request->all());

        return redirect()->route('hr.deductions.index')
                        ->with('success', 'Deduction updated successfully.');
    }

    public function destroy($id)
    {
        $deduction = Deduction::findOrFail($id);
        $deduction->delete();

        return redirect()->route('hr.deductions.index')
                        ->with('success', 'Deduction deleted successfully.');
    }
}
