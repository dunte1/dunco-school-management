<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AcademicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the academic dashboard
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        // Get statistics
        $totalClasses = AcademicClass::where('school_id', $schoolId)->count();
        $activeClasses = AcademicClass::where('school_id', $schoolId)->where('is_active', true)->count();
        $totalSubjects = Subject::where('school_id', $schoolId)->count();
        $totalStudents = User::where('school_id', $schoolId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->count();

        // Get recent classes
        $recentClasses = AcademicClass::with(['teacher'])
            ->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent subjects
        $recentSubjects = Subject::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('academic::dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academic::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('academic::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('academic::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
