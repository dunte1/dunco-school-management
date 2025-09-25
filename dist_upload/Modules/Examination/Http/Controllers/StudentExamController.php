<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StudentExamController extends Controller
{
    public function index()
    {
        return view('examination::student-exams.index');
    }

    public function show($id)
    {
        return view('examination::student-exams.show');
    }

    public function start($examId)
    {
        return view('examination::student-exams.start');
    }

    public function submit(Request $request, $examId)
    {
        // Exam submission logic
        return redirect()->route('examination.student-exams.index');
    }

    public function result($examId)
    {
        return view('examination::student-exams.result');
    }

    public function history()
    {
        return view('examination::student-exams.history');
    }
}
