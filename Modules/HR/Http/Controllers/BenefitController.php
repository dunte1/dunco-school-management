<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Benefit;

class BenefitController extends Controller
{
    public function index()
    {
        $benefits = Benefit::paginate(15);
        return view('hr::benefits.index', compact('benefits'));
    }

    public function create()
    {
        return view('hr::benefits.create');
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

        Benefit::create($request->all());

        return redirect()->route('hr.benefits.index')
                        ->with('success', 'Benefit created successfully.');
    }

    public function show($id)
    {
        $benefit = Benefit::findOrFail($id);
        return view('hr::benefits.show', compact('benefit'));
    }

    public function edit($id)
    {
        $benefit = Benefit::findOrFail($id);
        return view('hr::benefits.edit', compact('benefit'));
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

        $benefit = Benefit::findOrFail($id);
        $benefit->update($request->all());

        return redirect()->route('hr.benefits.index')
                        ->with('success', 'Benefit updated successfully.');
    }

    public function destroy($id)
    {
        $benefit = Benefit::findOrFail($id);
        $benefit->delete();

        return redirect()->route('hr.benefits.index')
                        ->with('success', 'Benefit deleted successfully.');
    }
}
