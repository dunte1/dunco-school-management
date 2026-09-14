<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\ExamType;

class ExamTypeController extends Controller
{
    public function index()
    {
        $types = ExamType::orderBy('name')->get();

        return view('examination::type.index', compact('types'));
    }

    public function create()
    {
        return view('examination::type.create');
    }

    public function store(Request $request)
    {
        ExamType::create($this->validated($request));

        return redirect()->route('examination.exam-types.index')->with('success', 'Exam type created.');
    }

    public function show($id)
    {
        $type = ExamType::findOrFail($id);

        return view('examination::type.show', compact('type'));
    }

    public function edit($id)
    {
        $type = ExamType::findOrFail($id);

        return view('examination::type.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = ExamType::findOrFail($id);
        $type->update($this->validated($request, $type->id));

        return redirect()->route('examination.exam-types.index')->with('success', 'Exam type updated.');
    }

    public function destroy($id)
    {
        ExamType::findOrFail($id)->delete();

        return redirect()->route('examination.exam-types.index')->with('success', 'Exam type deleted.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        $unique = 'unique:exam_types,code'.($ignoreId ? ','.$ignoreId : '');

        return $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|'.$unique,
            'description' => 'nullable|string',
            'is_online' => 'boolean',
            'is_active' => 'boolean',
        ]);
    }
}
