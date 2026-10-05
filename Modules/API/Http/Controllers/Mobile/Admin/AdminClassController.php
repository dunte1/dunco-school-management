<?php

namespace Modules\API\Http\Controllers\Mobile\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Models\AcademicClass;

class AdminClassController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $classes = AcademicClass::where('school_id', $schoolId)->paginate(20);
            
            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch classes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $class = AcademicClass::where('school_id', $schoolId)->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $class
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch class',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
            
            $data['school_id'] = $request->user()->school_id ?? 1;
            $class = AcademicClass::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Class created successfully',
                'data' => $class
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create class',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $class = AcademicClass::where('school_id', $schoolId)->findOrFail($id);
            
            $data = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
            ]);
            
            $class->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully',
                'data' => $class
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update class',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $class = AcademicClass::where('school_id', $schoolId)->findOrFail($id);
            $class->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Class deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete class',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // Additional methods for class management
    public function getStudents(Request $request, $id): JsonResponse
    {
        return response()->json(['success' => true, 'data' => []]);
    }
    
    public function addStudents(Request $request, $id): JsonResponse
    {
        return response()->json(['success' => true, 'message' => 'Students added successfully']);
    }
    
    public function removeStudent(Request $request, $id, $studentId): JsonResponse
    {
        return response()->json(['success' => true, 'message' => 'Student removed successfully']);
    }
    
    public function getSubjects(Request $request, $id): JsonResponse
    {
        return response()->json(['success' => true, 'data' => []]);
    }
    
    public function assignSubjects(Request $request, $id): JsonResponse
    {
        return response()->json(['success' => true, 'message' => 'Subjects assigned successfully']);
    }
    
    public function getTimetable(Request $request, $id): JsonResponse
    {
        return response()->json(['success' => true, 'data' => []]);
    }
    
    public function updateTimetable(Request $request, $id): JsonResponse
    {
        return response()->json(['success' => true, 'message' => 'Timetable updated successfully']);
    }
}
