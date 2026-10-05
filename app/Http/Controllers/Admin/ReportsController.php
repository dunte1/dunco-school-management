<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function global(Request $request)
    {
        $this->middleware(['auth','role:admin']);

        $summary = [
            'schools' => \App\Models\School::count(),
            'users' => \App\Models\User::count(),
            'students' => class_exists('Modules\\Academic\\Models\\Student') ? \Modules\Academic\Models\Student::count() : 0,
            'teachers' => class_exists('Modules\\HR\\Models\\Staff') ? \Modules\HR\Models\Staff::where('role', 'teacher')->count() : 0,
            'payments' => class_exists('Modules\\Finance\\Models\\Payment') ? \Modules\Finance\Models\Payment::sum('amount') : 0,
            'invoices_overdue' => class_exists('Modules\\Finance\\Models\\Invoice') ? \Modules\Finance\Models\Invoice::where('status', 'unpaid')->count() : 0,
            'recent_logs' => \App\Models\AuditLog::latest()->take(20)->get(),
        ];

        return view('admin.reports.global', compact('summary'));
    }
}


