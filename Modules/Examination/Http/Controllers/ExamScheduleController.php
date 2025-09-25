<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamScheduleController extends Controller
{
    public function index()
    {
        return view('examination::schedules.index');
    }

    public function create()
    {
        return view('examination::schedules.create');
    }

    public function store(Request $request)
    {
        // Exam schedule creation logic
        return redirect()->route('examination.schedules.index');
    }

    public function show($id)
    {
        return view('examination::schedules.show');
    }

    public function edit($id)
    {
        return view('examination::schedules.edit');
    }

    public function update(Request $request, $id)
    {
        // Exam schedule update logic
        return redirect()->route('examination.schedules.index');
    }

    public function destroy($id)
    {
        // Exam schedule delete logic
        return redirect()->route('examination.schedules.index');
    }

    public function timetable()
    {
        return view('examination::schedules.timetable');
    }
}
