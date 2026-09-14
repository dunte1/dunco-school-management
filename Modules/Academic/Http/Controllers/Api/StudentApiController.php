<?php

namespace Modules\Academic\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Student;

class StudentApiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 25), 100);

        $students = Student::query()
            ->orderBy('name')
            ->paginate($perPage);

        return response()->json($students);
    }
}
