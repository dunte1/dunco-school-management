<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamScheduleController extends Controller
{
    public function index()
    {
        return view('examination::schedule.index');
    }

    public function create()
    {
        return view('examination::schedule.create');
    }

    public function store(Request $request)
    {
        // Exam schedule creation logic
        return redirect()->route('examination.schedule.index');
    }

    public function show($id)
    {
        return view('examination::schedule.show');
    }

    public function edit($id)
    {
        return view('examination::schedule.edit');
    }

    public function update(Request $request, $id)
    {
        // Exam schedule update logic
        return redirect()->route('examination.schedule.index');
    }

    public function destroy($id)
    {
        // Exam schedule delete logic
        return redirect()->route('examination.schedule.index');
    }
}
