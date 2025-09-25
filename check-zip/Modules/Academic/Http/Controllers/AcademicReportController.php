<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;

class AcademicReportController extends Controller
{
    public function index()
    {
        return view('academic::reports.index');
    }
} 
 