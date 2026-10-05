<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::paginate(15);
        return view('hr::positions.index', compact('positions'));
    }

    public function create()
    {
        return view('hr::positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'salary_range' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Position::create($request->all());

        return redirect()->route('hr.positions.index')
                        ->with('success', 'Position created successfully.');
    }

    public function show($id)
    {
        $position = Position::findOrFail($id);
        return view('hr::positions.show', compact('position'));
    }

    public function edit($id)
    {
        $position = Position::findOrFail($id);
        return view('hr::positions.edit', compact('position'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'salary_range' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $position = Position::findOrFail($id);
        $position->update($request->all());

        return redirect()->route('hr.positions.index')
                        ->with('success', 'Position updated successfully.');
    }

    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return redirect()->route('hr.positions.index')
                        ->with('success', 'Position deleted successfully.');
    }
}
