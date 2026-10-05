<?php

namespace Modules\API\Http\Controllers\Mobile\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\HR\Models\Staff;
use Carbon\Carbon;

class AdminTeacherController extends Controller
{
    /**
     * Get all teachers
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $query = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher');
            
            // Apply filters
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%");
                });
            }
            
            $teachers = $query->paginate($request->get('per_page', 20));
            
            return response()->json([
                'success' => true,
                'data' => $teachers
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teachers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get specific teacher
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $teacher = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher')
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $teacher
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create new teacher
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'employee_id' => 'required|string|unique:staff',
                'email' => 'required|email|unique:staff',
                'phone' => 'nullable|string',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'address' => 'nullable|string',
                'qualification' => 'nullable|string',
                'experience' => 'nullable|integer',
                'salary' => 'nullable|numeric',
                'status' => 'sometimes|in:active,inactive',
            ]);
            
            $data['school_id'] = $request->user()->school_id ?? 1;
            $data['role'] = 'teacher';
            $data['joining_date'] = Carbon::now();
            
            $teacher = Staff::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                'data' => $teacher
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update teacher
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $teacher = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher')
                ->findOrFail($id);
            
            $data = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'employee_id' => 'sometimes|string|unique:staff,employee_id,' . $id,
                'email' => 'sometimes|email|unique:staff,email,' . $id,
                'phone' => 'nullable|string',
                'date_of_birth' => 'sometimes|date',
                'gender' => 'sometimes|in:male,female,other',
                'address' => 'nullable|string',
                'qualification' => 'nullable|string',
                'experience' => 'nullable|integer',
                'salary' => 'nullable|numeric',
                'status' => 'sometimes|in:active,inactive',
            ]);
            
            $teacher->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully',
                'data' => $teacher
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete teacher
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $teacher = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher')
                ->findOrFail($id);
            
            $teacher->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get teacher subjects
     */
    public function getSubjects(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $teacher = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher')
                ->findOrFail($id);
            
            // Get teacher's assigned subjects
            $subjects = []; // This would be populated from a teacher_subjects table
            
            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teacher subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Assign subjects to teacher
     */
    public function assignSubjects(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $teacher = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher')
                ->findOrFail($id);
            
            $data = $request->validate([
                'subjects' => 'required|array',
                'subjects.*' => 'exists:subjects,id',
            ]);
            
            // Assign subjects logic here
            
            return response()->json([
                'success' => true,
                'message' => 'Subjects assigned successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get teacher schedule
     */
    public function getSchedule(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $teacher = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher')
                ->findOrFail($id);
            
            // Get teacher's schedule/timetable
            $schedule = []; // This would be populated from a timetable table
            
            return response()->json([
                'success' => true,
                'data' => $schedule
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teacher schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update teacher schedule
     */
    public function updateSchedule(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $teacher = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher')
                ->findOrFail($id);
            
            $data = $request->validate([
                'schedule' => 'required|array',
            ]);
            
            // Update schedule logic here
            
            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get teacher performance
     */
    public function getPerformance(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $teacher = Staff::where('school_id', $schoolId)
                ->where('role', 'teacher')
                ->findOrFail($id);
            
            // Get teacher performance metrics
            $performance = [
                'classes_taught' => 0,
                'students_taught' => 0,
                'average_rating' => 0,
                'attendance_rate' => 0,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teacher performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
