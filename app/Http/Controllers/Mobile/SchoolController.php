<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Get all active schools
     */
    public function index()
    {
        $schools = School::where('is_active', true)
            ->select(['id', 'name', 'code', 'motto', 'logo'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schools,
            'message' => 'Schools retrieved successfully'
        ]);
    }

    /**
     * Get school by ID
     */
    public function show($id)
    {
        $school = School::find($id);
        
        if (!$school) {
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $school,
            'message' => 'School retrieved successfully'
        ]);
    }
}
