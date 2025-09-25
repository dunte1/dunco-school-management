<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProctoringController extends Controller
{
    public function index()
    {
        return view('examination::proctoring.index');
    }

    public function monitor($examId)
    {
        return view('examination::proctoring.monitor');
    }

    public function logs($examId)
    {
        return view('examination::proctoring.logs');
    }

    public function settings()
    {
        return view('examination::proctoring.settings');
    }

    public function updateSettings(Request $request)
    {
        // Proctoring settings update logic
        return redirect()->route('examination.proctoring.settings')->with('success', 'Settings updated');
    }
}
