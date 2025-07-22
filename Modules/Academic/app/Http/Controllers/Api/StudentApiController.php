<?php

namespace Modules\Academic\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Models\Student;

class StudentApiController extends Controller
{
    public function index(Request $request)
    {
        // Simple token check (replace with Passport/Sanctum for production)
        if ($request->header('X-API-TOKEN') !== env('API_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $students = Student::with('class')->get(['id','name','admission_number','class_id']);
        return response()->json($students);
    }
} 