<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamController extends Controller
{
    public function index()
    {
        return view('examination::index');
    }

    public function create()
    {
        return view('examination::create');
    }

    public function store(Request $request)
    {
        // Exam creation logic
        return redirect()->route('examination.index');
    }

    public function show($id)
    {
        return view('examination::show');
    }

    public function edit($id)
    {
        return view('examination::edit');
    }

    public function update(Request $request, $id)
    {
        // Exam update logic
        return redirect()->route('examination.index');
    }

    public function destroy($id)
    {
        // Exam delete logic
        return redirect()->route('examination.index');
    }
}
