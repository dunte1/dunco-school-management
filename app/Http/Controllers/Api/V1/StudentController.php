<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function attendanceSummary($studentId)
    {
        $present = \Modules\Academic\Models\AttendanceRecord::where('student_id', $studentId)->where('status','present')->count();
        $absent = \Modules\Academic\Models\AttendanceRecord::where('student_id', $studentId)->where('status','absent')->count();
        $late = \Modules\Academic\Models\AttendanceRecord::where('student_id', $studentId)->where('status','late')->count();
        return response()->json(compact('present','absent','late'));
    }

    public function resultsSummary($studentId)
    {
        // Placeholder summary; replace with real aggregation
        $results = [
            'gpa' => 3.6,
            'latest_exam' => 'Term 1 2024',
        ];
        return response()->json($results);
    }
}


