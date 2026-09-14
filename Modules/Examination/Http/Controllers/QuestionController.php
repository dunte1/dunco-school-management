<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class QuestionController extends Controller
{
    public function index()
    {
        return view('examination::questions.index');
    }

    public function create()
    {
        return view('examination::questions.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('examination.questions.index');
    }

    public function show($id)
    {
        return view('examination::questions.show');
    }

    public function edit($id)
    {
        $question = \Modules\Examination\Models\Question::findOrFail($id);

        return view('examination::questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('examination.questions.index');
    }

    public function destroy($id)
    {
        return redirect()->route('examination.questions.index');
    }

    public function import(Request $request)
    {
        return redirect()->back()->with('success', 'Questions imported successfully');
    }

    public function export()
    {
        return response()->download('path/to/questions.csv');
    }
}
