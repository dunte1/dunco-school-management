<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function children(Request $request)
    {
        $user = $request->user();
        $children = collect();
        
        try {
            if (class_exists('Modules\\Academic\\Models\\Student')) {
                $children = \Modules\Academic\Models\Student::whereHas('parents', function($q) use ($user) {
                    $q->where('parent_id', $user->id);
                })->get(['id','name','class_id']);
            }
        } catch (\Throwable $e) {
            try {
                // Fallback if relation not available
                $children = \Modules\Academic\Models\Student::where('parent_id', $user->id)->get(['id','name','class_id']);
            } catch (\Throwable $e2) { 
                $children = collect(); 
            }
        }
        
        return response()->json($children->map(function($c){
            return [
                'id' => $c->id,
                'name' => $c->name,
                'class_id' => $c->class_id,
            ];
        }));
    }

    public function childAttendance(Request $request, $id)
    {
        $user = $request->user();
        
        // Verify the child belongs to this parent
        try {
            $child = \Modules\Academic\Models\Student::whereHas('parents', function($q) use ($user) {
                $q->where('parent_id', $user->id);
            })->where('id', $id)->first();
            
            if (!$child) {
                return response()->json(['message' => 'Child not found'], 404);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Child not found'], 404);
        }

        // Return attendance data for the child
        return response()->json([
            'child_id' => $id,
            'attendance' => [],
            'message' => 'Attendance data will be available soon'
        ]);
    }

    public function childResults(Request $request, $id)
    {
        $user = $request->user();
        
        // Verify the child belongs to this parent
        try {
            $child = \Modules\Academic\Models\Student::whereHas('parents', function($q) use ($user) {
                $q->where('parent_id', $user->id);
            })->where('id', $id)->first();
            
            if (!$child) {
                return response()->json(['message' => 'Child not found'], 404);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Child not found'], 404);
        }

        // Return exam results for the child
        return response()->json([
            'child_id' => $id,
            'results' => [],
            'message' => 'Exam results will be available soon'
        ]);
    }

    public function childFees(Request $request, $id)
    {
        $user = $request->user();
        
        // Verify the child belongs to this parent
        try {
            $child = \Modules\Academic\Models\Student::whereHas('parents', function($q) use ($user) {
                $q->where('parent_id', $user->id);
            })->where('id', $id)->first();
            
            if (!$child) {
                return response()->json(['message' => 'Child not found'], 404);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Child not found'], 404);
        }

        // Return fee information for the child
        return response()->json([
            'child_id' => $id,
            'fees' => [],
            'message' => 'Fee information will be available soon'
        ]);
    }
}
