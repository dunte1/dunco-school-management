<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class QuestionCategoryController extends Controller
{
    public function index()
    {
        return view('examination::question-categories.index');
    }

    public function create()
    {
        return view('examination::question-categories.create');
    }

    public function store(Request $request)
    {
        // Question category creation logic
        return redirect()->route('examination.question-categories.index');
    }

    public function show($id)
    {
        return view('examination::question-categories.show');
    }

    public function edit($id)
    {
        return view('examination::question-categories.edit');
    }

    public function update(Request $request, $id)
    {
        // Question category update logic
        return redirect()->route('examination.question-categories.index');
    }

    public function destroy($id)
    {
        // Question category delete logic
        return redirect()->route('examination.question-categories.index');
    }
}
