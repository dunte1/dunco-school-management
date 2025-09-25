<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OnlineExamController extends Controller
{
    public function index()
    {
        return view('examination::online.index');
    }

    public function start($examId)
    {
        return view('examination::online.start');
    }

    public function submit(Request $request, $examId)
    {
        // Online exam submission logic
        return redirect()->route('examination.online.index');
    }

    public function result($examId)
    {
        return view('examination::online.result');
    }

    public function proctoring($examId)
    {
        return view('examination::online.proctoring');
    }
}
