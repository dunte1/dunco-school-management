<?php

namespace Modules\Academic\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\StudentFee;
use Modules\Academic\Models\StudentDocument;

class ReportController extends Controller
{
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalFeesCollected = StudentFee::where('status', 'paid')->sum('amount');
        $totalOutstanding = StudentFee::where('status', '!=', 'paid')->sum('amount');
        // Enrollment by class
        $enrollmentByClass = Student::selectRaw('class_id, count(*) as total')->groupBy('class_id')->pluck('total', 'class_id');
        // Fee collection trend (by month)
        $feeTrend = StudentFee::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, sum(amount) as total')
            ->where('status', 'paid')
            ->groupBy('month')->orderBy('month')->pluck('total', 'month');
        // Compliance: students missing any required document
        $studentsWithDocs = StudentDocument::pluck('student_id')->unique();
        $studentsMissingDocs = Student::whereNotIn('id', $studentsWithDocs)->count();
        return view('academic::reports.dashboard', [
            'totalStudents' => $totalStudents,
            'totalFeesCollected' => $totalFeesCollected,
            'totalOutstanding' => $totalOutstanding,
            'enrollmentByClass' => $enrollmentByClass,
            'feeTrend' => $feeTrend,
            'studentsMissingDocs' => $studentsMissingDocs,
        ]);
    }
} 