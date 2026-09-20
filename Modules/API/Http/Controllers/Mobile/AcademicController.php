<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\AttendanceRecord;
use Modules\Academic\Models\Exam;
use Modules\Academic\Models\ExamResult;
use Modules\Academic\Models\Grade;
use Modules\Academic\Models\AcademicRecord;
use Modules\Timetable\Models\ClassSchedule;

class AcademicController extends Controller
{
    public function getClasses(Request $request): JsonResponse
    {
        try {
            $query = AcademicClass::orderBy('name');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            $classes = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Classes retrieved successfully',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve classes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClass($id): JsonResponse
    {
        try {
            $class = AcademicClass::with(['section', 'teacher'])->find($id);

            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Class retrieved successfully',
                'data' => $class
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve class: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSections(Request $request): JsonResponse
    {
        try {
            $sections = \DB::table('academic_class_sections')
                ->select('id', 'name', 'class_id')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Sections retrieved successfully',
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sections: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSubjects(Request $request): JsonResponse
    {
        try {
            $query = Subject::orderBy('name');

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            $subjects = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Subjects retrieved successfully',
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSubject($id): JsonResponse
    {
        try {
            $subject = Subject::with(['class'])->find($id);

            if (!$subject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Subject retrieved successfully',
                'data' => $subject
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subject: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudents(Request $request): JsonResponse
    {
        try {
            $query = Student::with(['class']);

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('admission_number', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            $students = $query->orderBy('name')->paginate($request->get('per_page', 25));

            return response()->json([
                'success' => true,
                'message' => 'Students retrieved successfully',
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve students: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudent($id): JsonResponse
    {
        try {
            $student = Student::with(['class', 'fees'])->find($id);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Student retrieved successfully',
                'data' => $student
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve student: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAttendance(Request $request, $studentId = null): JsonResponse
    {
        try {
            $userId = Auth::id();

            if ($studentId) {
                $student = Student::find($studentId);
                if (!$student) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student not found'
                    ], 404);
                }
            } else {
                $student = Student::where('user_id', $userId)->first();
                if (!$student) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student profile not found'
                    ], 404);
                }
                $studentId = $student->id;
            }

            $query = AttendanceRecord::where('student_id', $studentId);

            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            if ($request->filled('month')) {
                $query->whereMonth('date', $request->month);
            }

            if ($request->filled('year')) {
                $query->whereYear('date', $request->year);
            }

            $records = $query->orderByDesc('date')
                ->limit($request->get('limit', 30))
                ->get();

            $totalDays = $records->count();
            $presentDays = $records->where('status', 'present')->count();
            $absentDays = $records->where('status', 'absent')->count();
            $lateDays = $records->where('status', 'late')->count();

            return response()->json([
                'success' => true,
                'message' => 'Attendance retrieved successfully',
                'data' => [
                    'records' => $records,
                    'summary' => [
                        'total_days' => $totalDays,
                        'present_days' => $presentDays,
                        'absent_days' => $absentDays,
                        'late_days' => $lateDays,
                        'attendance_rate' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTimetable(Request $request): JsonResponse
    {
        try {
            $query = ClassSchedule::with(['teacher', 'room']);

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->filled('day')) {
                $query->where('day', $request->day);
            }

            if ($request->filled('teacher_id')) {
                $query->where('teacher_id', $request->teacher_id);
            }

            $schedules = $query->orderBy('day')
                ->orderBy('start_time')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Timetable retrieved successfully',
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve timetable: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTimetableById($id): JsonResponse
    {
        try {
            $schedule = ClassSchedule::with(['teacher', 'room', 'class', 'subject'])->find($id);

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timetable entry not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Timetable entry retrieved successfully',
                'data' => $schedule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve timetable entry: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getExams(Request $request): JsonResponse
    {
        try {
            $query = Exam::with(['classes']);

            if ($request->filled('class_id')) {
                $query->whereHas('classes', function ($q) use ($request) {
                    $q->where('academic_classes.id', $request->class_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $exams = $query->orderByDesc('start_date')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Exams retrieved successfully',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve exams: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getExamResults(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student profile not found'
                ], 404);
            }

            $query = ExamResult::with(['exam', 'subject'])
                ->where('student_id', $student->id);

            if ($request->filled('exam_id')) {
                $query->where('exam_id', $request->exam_id);
            }

            $results = $query->orderByDesc('created_at')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Exam results retrieved successfully',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve exam results: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getGrades(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student profile not found'
                ], 404);
            }

            $query = Grade::with(['gradingScale'])
                ->where('student_id', $student->id);

            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }

            if ($request->filled('term')) {
                $query->where('term', $request->term);
            }

            $grades = $query->orderByDesc('created_at')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Grades retrieved successfully',
                'data' => $grades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve grades: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getGrade($id): JsonResponse
    {
        try {
            $grade = Grade::with(['gradingScale', 'student', 'subject'])->find($id);

            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grade not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Grade retrieved successfully',
                'data' => $grade
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve grade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createGrade(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:students,id',
                'subject_id' => 'required|exists:subjects,id',
                'score' => 'required|numeric|min:0|max:100',
                'term' => 'required|string|max:50',
                'academic_session' => 'nullable|string|max:50',
                'remarks' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $grade = Grade::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Grade created successfully',
                'data' => $grade
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create grade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateGrade(Request $request, $id): JsonResponse
    {
        try {
            $grade = Grade::find($id);

            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grade not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'score' => 'sometimes|numeric|min:0|max:100',
                'term' => 'sometimes|string|max:50',
                'academic_session' => 'nullable|string|max:50',
                'remarks' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $grade->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully',
                'data' => $grade
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update grade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteGrade($id): JsonResponse
    {
        try {
            $grade = Grade::find($id);

            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grade not found'
                ], 404);
            }

            $grade->delete();

            return response()->json([
                'success' => true,
                'message' => 'Grade deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete grade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAcademicRecords(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student profile not found'
                ], 404);
            }

            $records = AcademicRecord::with(['class', 'subject'])
                ->where('student_id', $student->id)
                ->orderByDesc('created_at')
                ->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Academic records retrieved successfully',
                'data' => $records
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve academic records: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentCount(Request $request): JsonResponse
    {
        try {
            $query = Student::where('is_active', true);

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            $count = $query->count();

            return response()->json([
                'success' => true,
                'message' => 'Student count retrieved successfully',
                'data' => ['count' => $count]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve student count: ' . $e->getMessage()
            ], 500);
        }
    }
}
