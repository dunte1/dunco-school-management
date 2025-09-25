<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamTypeController extends Controller
{
    public function index()
    {
        return view('examination::type.index');
    }

    public function create()
    {
        return view('examination::type.create');
    }

    public function store(Request $request)
    {
        // Exam type creation logic
        return redirect()->route('examination.type.index');
    }

    public function show($id)
    {
        return view('examination::type.show');
    }

    public function edit($id)
    {
        return view('examination::type.edit');
    }

    public function update(Request $request, $id)
    {
        // Exam type update logic
        return redirect()->route('examination.type.index');
    }

    public function destroy($id)
    {
        // Exam type delete logic
        return redirect()->route('examination.type.index');
    }
}
