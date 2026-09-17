<?php

namespace Modules\API\Http\Controllers\Mobile;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class AcademicController extends Controller
{
    public function getClasses(): JsonResponse
    {
        $classes = \Modules\Academic\Models\AcademicClass::orderBy('name')->get();
        return response()->json(['success' => true, 'data' => $classes]);
    }

    public function getClass($id): JsonResponse
    {
        $class = \Modules\Academic\Models\AcademicClass::find($id);
        if (!$class) return response()->json(['message' => 'Class not found'], 404);
        return response()->json(['success' => true, 'data' => $class]);
    }

    public function getSections(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => []]);
    }

    public function getSubjects(): JsonResponse
    {
        $subjects = \Modules\Academic\Models\Subject::orderBy('name')->get();
        return response()->json(['success' => true, 'data' => $subjects]);
    }

    public function getSubject($id): JsonResponse
    {
        $subject = \Modules\Academic\Models\Subject::find($id);
        if (!$subject) return response()->json(['message' => 'Subject not found'], 404);
        return response()->json(['success' => true, 'data' => $subject]);
    }

    public function getStudents(): JsonResponse
    {
        $students = \Modules\Academic\Models\Student::with('class')->orderBy('name')->paginate(25);
        return response()->json(['success' => true, 'data' => $students]);
    }

    public function getStudent($id): JsonResponse
    {
        $student = \Modules\Academic\Models\Student::with(['class', 'fees'])->find($id);
        if (!$student) return response()->json(['message' => 'Student not found'], 404);
        return response()->json(['success' => true, 'data' => $student]);
    }

    public function getAttendance($studentId): JsonResponse
    {
        $records = \Modules\Academic\Models\AttendanceRecord::where('student_id', $studentId)
            ->orderByDesc('date')->limit(30)->get();
        return response()->json(['success' => true, 'data' => $records]);
    }

    public function getTimetable(): JsonResponse
    {
        $schedules = \Modules\Timetable\Models\ClassSchedule::with(['teacher', 'room'])->orderBy('day')->get();
        return response()->json(['success' => true, 'data' => $schedules]);
    }
}
