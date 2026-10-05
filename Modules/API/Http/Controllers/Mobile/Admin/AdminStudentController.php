<?php

namespace Modules\API\Http\Controllers\Mobile\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\AcademicClass;
use Modules\Attendance\Models\Attendance;
use Carbon\Carbon;

class AdminStudentController extends Controller
{
    /**
     * Get all students
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $query = Student::where('school_id', $schoolId)
                ->with(['class', 'section']);
            
            // Apply filters
            if ($request->has('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            
            if ($request->has('section_id')) {
                $query->where('section_id', $request->section_id);
            }
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('admission_no', 'like', "%{$search}%");
                });
            }
            
            $students = $query->paginate($request->get('per_page', 20));
            
            return response()->json([
                'success' => true,
                'data' => $students
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch students',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get specific student
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $student = Student::where('school_id', $schoolId)
                ->with(['class', 'section', 'parent'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $student
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create new student
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'admission_no' => 'required|string|unique:students',
                'class_id' => 'required|exists:academic_classes,id',
                'section_id' => 'required|exists:sections,id',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'blood_group' => 'nullable|string',
                'religion' => 'nullable|string',
                'caste' => 'nullable|string',
                'phone' => 'nullable|string',
                'email' => 'nullable|email',
                'address' => 'nullable|string',
                'parent_name' => 'nullable|string',
                'parent_phone' => 'nullable|string',
                'parent_email' => 'nullable|email',
                'status' => 'sometimes|in:active,inactive',
            ]);
            
            $data['school_id'] = $request->user()->school_id ?? 1;
            $data['admission_date'] = Carbon::now();
            
            $student = Student::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'data' => $student
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update student
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $student = Student::where('school_id', $schoolId)->findOrFail($id);
            
            $data = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'admission_no' => 'sometimes|string|unique:students,admission_no,' . $id,
                'class_id' => 'sometimes|exists:academic_classes,id',
                'section_id' => 'sometimes|exists:sections,id',
                'date_of_birth' => 'sometimes|date',
                'gender' => 'sometimes|in:male,female,other',
                'blood_group' => 'nullable|string',
                'religion' => 'nullable|string',
                'caste' => 'nullable|string',
                'phone' => 'nullable|string',
                'email' => 'nullable|email',
                'address' => 'nullable|string',
                'parent_name' => 'nullable|string',
                'parent_phone' => 'nullable|string',
                'parent_email' => 'nullable|email',
                'status' => 'sometimes|in:active,inactive',
            ]);
            
            $student->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => $student
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete student
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $student = Student::where('school_id', $schoolId)->findOrFail($id);
            $student->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get student attendance
     */
    public function getAttendance(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $student = Student::where('school_id', $schoolId)->findOrFail($id);
            
            $query = Attendance::where('student_id', $id)
                ->where('school_id', $schoolId);
            
            if ($request->has('from_date')) {
                $query->whereDate('date', '>=', $request->from_date);
            }
            
            if ($request->has('to_date')) {
                $query->whereDate('date', '<=', $request->to_date);
            }
            
            $attendance = $query->orderBy('date', 'desc')->paginate(30);
            
            // Calculate attendance summary
            $totalDays = $attendance->total();
            $presentDays = $attendance->where('status', 'present')->count();
            $absentDays = $attendance->where('status', 'absent')->count();
            $percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'attendance' => $attendance,
                    'summary' => [
                        'total_days' => $totalDays,
                        'present_days' => $presentDays,
                        'absent_days' => $absentDays,
                        'percentage' => $percentage,
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get student academic record
     */
    public function getAcademicRecord(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $student = Student::where('school_id', $schoolId)->findOrFail($id);
            
            // Get academic record data
            $academicRecord = [
                'student' => $student,
                'current_class' => $student->class,
                'subjects' => [], // Get student subjects
                'exam_results' => [], // Get exam results
                'attendance_summary' => [], // Get attendance summary
            ];
            
            return response()->json([
                'success' => true,
                'data' => $academicRecord
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch academic record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Promote student
     */
    public function promote(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $student = Student::where('school_id', $schoolId)->findOrFail($id);
            
            $data = $request->validate([
                'new_class_id' => 'required|exists:academic_classes,id',
                'new_section_id' => 'required|exists:sections,id',
            ]);
            
            $student->update([
                'class_id' => $data['new_class_id'],
                'section_id' => $data['new_section_id'],
                'promotion_date' => Carbon::now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Student promoted successfully',
                'data' => $student
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to promote student',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Demote student
     */
    public function demote(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $student = Student::where('school_id', $schoolId)->findOrFail($id);
            
            $data = $request->validate([
                'new_class_id' => 'required|exists:academic_classes,id',
                'new_section_id' => 'required|exists:sections,id',
                'reason' => 'required|string',
            ]);
            
            $student->update([
                'class_id' => $data['new_class_id'],
                'section_id' => $data['new_section_id'],
                'demotion_date' => Carbon::now(),
                'demotion_reason' => $data['reason'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Student demoted successfully',
                'data' => $student
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to demote student',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk import students
     */
    public function bulkImport(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'students' => 'required|array',
                'students.*.first_name' => 'required|string|max:255',
                'students.*.last_name' => 'required|string|max:255',
                'students.*.admission_no' => 'required|string',
                'students.*.class_id' => 'required|exists:academic_classes,id',
                'students.*.section_id' => 'required|exists:sections,id',
            ]);
            
            $schoolId = $request->user()->school_id ?? 1;
            $imported = 0;
            $errors = [];
            
            foreach ($data['students'] as $index => $studentData) {
                try {
                    $studentData['school_id'] = $schoolId;
                    $studentData['admission_date'] = Carbon::now();
                    Student::create($studentData);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Imported {$imported} students successfully",
                'data' => [
                    'imported' => $imported,
                    'errors' => $errors,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import students',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export students
     */
    public function export(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $students = Student::where('school_id', $schoolId)
                ->with(['class', 'section'])
                ->get();
            
            // Generate export data
            $exportData = $students->map(function($student) {
                return [
                    'Admission No' => $student->admission_no,
                    'Name' => $student->first_name . ' ' . $student->last_name,
                    'Class' => $student->class->name ?? '',
                    'Section' => $student->section->name ?? '',
                    'Phone' => $student->phone,
                    'Email' => $student->email,
                    'Status' => $student->status,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $exportData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export students',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
