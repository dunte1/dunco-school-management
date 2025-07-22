<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AuditLog;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\Student;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $stats = [
            'users' => User::count(),
            'schools' => School::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'audit_logs' => AuditLog::count(),
        ];

        // Academic stats
        $academicStats = [
            'totalClasses' => Subject::count(),
            'totalSubjects' => Subject::count(),
            'totalStudents' => Student::count(),
            'activeClasses' => 0, // Replace with actual logic if available
        ];

        return view('dashboard', compact('stats', 'academicStats'));
    }
} 