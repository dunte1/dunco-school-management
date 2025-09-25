<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\AttendanceRecord;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Modules\HR\Models\Staff;
use Modules\HR\Models\StaffAttendanceRecord;
use Modules\Academic\Models\AttendanceExcuse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Modules\Academic\Models\AttendanceAuditLog;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display attendance dashboard
     */
    public function index(Request $request)
    {
        $classes = AcademicClass::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $selectedClass = null;
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        $selectedPeriod = $request->get('period');
        $attendanceRecords = collect();
        $staffList = collect();

        if ($request->get('attendance_type', 'student') === 'student' && $request->filled('class_id')) {
            $selectedClass = AcademicClass::with(['students'])
                ->where('school_id', Auth::user()->school_id)
                ->findOrFail($request->class_id);

            $attendanceRecords = AttendanceRecord::where('class_id', $selectedClass->id)
                ->where('date', $selectedDate)
                ->when($selectedPeriod, function($q) use ($selectedPeriod) {
                    $q->where('period', $selectedPeriod);
                })
                ->with(['student'])
                ->get()
                ->keyBy('student_id');
        } elseif ($request->get('attendance_type') === 'staff') {
            $staffList = Staff::where('school_id', Auth::user()->school_id)
                ->where('status', 'active')
                ->get();
        }

        return view('academic::attendance.index', compact('classes', 'selectedClass', 'selectedDate', 'attendanceRecords', 'staffList', 'selectedPeriod'));
    }

    /**
     * Mark attendance for a class
     */
    public function markAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:academic_classes,id',
            'date' => 'required|date',
            'period' => 'nullable|string|max:32',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:academic_students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused,sick,on_leave,suspended',
            'attendance.*.remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $classId = $request->class_id;
        $date = $request->date;
        $period = $request->period;

        // Delete existing records for this class, date, and period
        AttendanceRecord::where('class_id', $classId)
            ->where('date', $date)
            ->when($period, function($q) use ($period) {
                $q->where('period', $period);
            })
            ->delete();

        // Create new attendance records
        foreach ($request->attendance as $record) {
            AttendanceRecord::create([
                'school_id' => Auth::user()->school_id,
                'student_id' => $record['student_id'],
                'class_id' => $classId,
                'date' => $date,
                'period' => $period,
                'status' => $record['status'],
                'remarks' => $record['remarks'] ?? null,
                'marked_by' => Auth::id(),
                'is_active' => true,
            ]);
        }

        return response()->json(['message' => 'Attendance marked successfully!']);
    }

    /**
     * Get attendance report
     */
    public function report(Request $request)
    {
        $classes = AcademicClass::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $students = collect();
        $attendanceData = collect();
        $dateRange = null;

        if ($request->filled(['class_id', 'start_date', 'end_date'])) {
            $classId = $request->class_id;
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $dateRange = [$startDate, $endDate];

            $students = Student::whereHas('classes', function($query) use ($classId) {
                $query->where('academic_class_id', $classId);
            })->get();

            $attendanceData = AttendanceRecord::where('class_id', $classId)
                ->whereBetween('date', $dateRange)
                ->with(['student'])
                ->get()
                ->groupBy('student_id');
        }

        return view('academic::attendance.report', compact('classes', 'students', 'attendanceData', 'dateRange'));
    }

    /**
     * Get student attendance history
     */
    public function studentHistory($studentId, Request $request)
    {
        $student = Student::where('school_id', Auth::user()->school_id)
            ->findOrFail($studentId);

        $query = AttendanceRecord::where('student_id', $studentId)
            ->with(['class']);

        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendanceRecords = $query->orderBy('date', 'desc')->paginate(20);

        // Calculate statistics
        $totalDays = $attendanceRecords->total();
        $presentDays = $attendanceRecords->where('status', 'present')->count();
        $absentDays = $attendanceRecords->where('status', 'absent')->count();
        $lateDays = $attendanceRecords->where('status', 'late')->count();
        $excusedDays = $attendanceRecords->where('status', 'excused')->count();

        $attendanceRate = $totalDays > 0 ? round((($presentDays + $lateDays) / $totalDays) * 100, 2) : 0;

        return view('academic::attendance.student-history', compact(
            'student', 'attendanceRecords', 'totalDays', 'presentDays', 
            'absentDays', 'lateDays', 'excusedDays', 'attendanceRate'
        ));
    }

    /**
     * Bulk import attendance
     */
    public function bulkImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:academic_classes,id',
            'date' => 'required|date',
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Handle file upload and processing
        $file = $request->file('file');
        $classId = $request->class_id;
        $date = $request->date;

        // Process the file and import attendance
        // This is a simplified version - you might want to use a package like Maatwebsite Excel
        try {
            // Delete existing records for this class and date
            AttendanceRecord::where('class_id', $classId)
                ->where('date', $date)
                ->delete();

            // Process file and create attendance records
            // Implementation depends on the file format

            return redirect()->route('academic.attendance.index', ['class_id' => $classId, 'date' => $date])
                ->with('success', 'Attendance imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing attendance: ' . $e->getMessage());
        }
    }

    /**
     * Export attendance report
     */
    public function export(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:academic_classes,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'format' => 'required|in:csv,xlsx,pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $classId = $request->class_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $format = $request->format;

        $attendanceData = AttendanceRecord::where('class_id', $classId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['student', 'class'])
            ->get();

        // Generate and download the report
        // Implementation depends on the export format and library used

        return response()->download('path/to/generated/file.' . $format);
    }

    /**
     * Get attendance statistics
     */
    public function statistics(Request $request)
    {
        $classes = AcademicClass::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $selectedClass = null;
        $statistics = null;

        if ($request->filled('class_id')) {
            $selectedClass = AcademicClass::findOrFail($request->class_id);
            
            $startDate = $request->get('start_date', now()->startOfMonth());
            $endDate = $request->get('end_date', now()->endOfMonth());

            $statistics = $this->calculateAttendanceStatistics($selectedClass->id, $startDate, $endDate);
        }

        return view('academic::attendance.statistics', compact('classes', 'selectedClass', 'statistics'));
    }

    /**
     * Calculate attendance statistics
     */
    private function calculateAttendanceStatistics($classId, $startDate, $endDate)
    {
        $records = AttendanceRecord::where('class_id', $classId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalRecords = $records->count();
        $presentCount = $records->where('status', 'present')->count();
        $absentCount = $records->where('status', 'absent')->count();
        $lateCount = $records->where('status', 'late')->count();
        $excusedCount = $records->where('status', 'excused')->count();

        return [
            'total' => $totalRecords,
            'present' => $presentCount,
            'absent' => $absentCount,
            'late' => $lateCount,
            'excused' => $excusedCount,
            'attendance_rate' => $totalRecords > 0 ? round((($presentCount + $lateCount) / $totalRecords) * 100, 2) : 0,
            'absent_rate' => $totalRecords > 0 ? round(($absentCount / $totalRecords) * 100, 2) : 0,
        ];
    }

    public function markStaffAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'period' => 'nullable|string|max:32',
            'attendance' => 'required|array',
            'attendance.*.staff_id' => 'required|exists:staff,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused,sick,on_leave,suspended',
            'attendance.*.remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $date = $request->date;
        $period = $request->period;
        $schoolId = Auth::user()->school_id;

        // Delete existing records for this date, period, and school
        StaffAttendanceRecord::where('school_id', $schoolId)
            ->where('date', $date)
            ->when($period, function($q) use ($period) {
                $q->where('period', $period);
            })
            ->delete();

        foreach ($request->attendance as $record) {
            StaffAttendanceRecord::create([
                'school_id' => $schoolId,
                'staff_id' => $record['staff_id'],
                'date' => $date,
                'period' => $period,
                'status' => $record['status'],
                'remarks' => $record['remarks'] ?? null,
                'marked_by' => Auth::id(),
                'is_active' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Staff attendance marked successfully!');
    }

    public function submitExcuse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'period' => 'required|string|max:32',
            'reason' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $excuse = new AttendanceExcuse();
        // For demo: assume user is a student and has a student_id property
        $excuse->student_id = Auth::user()->student_id ?? null;
        $excuse->date = $request->date;
        $excuse->period = $request->period;
        $excuse->reason = $request->reason;
        $excuse->status = 'pending';

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('attendance_excuses', 'public');
            $excuse->document = $path;
        }

        $excuse->save();

        return redirect()->back()->with('success', 'Absence excuse submitted and pending review.');
    }

    public function adminExcuses(Request $request)
    {
        $query = AttendanceExcuse::query()->with(['student', 'staff']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }
        $excuses = $query->orderBy('date', 'desc')->paginate(20);
        return view('academic::attendance.excuses', compact('excuses'));
    }

    public function excuseAction(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);
        $excuse = AttendanceExcuse::findOrFail($id);
        $excuse->status = $request->action === 'approve' ? 'approved' : 'rejected';
        $excuse->reviewed_by = Auth::id();
        $excuse->reviewed_at = now();
        $excuse->save();
        return redirect()->back()->with('success', 'Excuse ' . $excuse->status . ' successfully.');
    }

    /**
     * API: Get student attendance analytics (monthly/yearly, chart-ready)
     */
    public function getStudentAnalytics(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $classId = $request->get('class_id');
        $type = $request->get('type', 'monthly'); // 'monthly' or 'yearly'
        $year = $request->get('year', now()->year);
        $statuses = ['present', 'absent', 'late', 'excused', 'sick', 'on_leave', 'suspended'];

        $query = AttendanceRecord::query()->where('school_id', $schoolId);
        if ($classId) {
            $query->where('class_id', $classId);
        }
        $query->whereYear('date', $year);

        $groupBy = $type === 'yearly' ? DB::raw('YEAR(date)') : DB::raw('MONTH(date)');
        $select = $type === 'yearly'
            ? [DB::raw('YEAR(date) as period')]
            : [DB::raw('MONTH(date) as period')];
        foreach ($statuses as $status) {
            $select[] = DB::raw("SUM(CASE WHEN status = '$status' THEN 1 ELSE 0 END) as $status");
        }
        $analytics = $query->select($select)
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Prepare chart data
        $labels = $analytics->pluck('period')->map(function($p) use ($type, $year) {
            return $type === 'yearly' ? $p : Carbon::create()->month($p)->format('M');
        });
        $datasets = [];
        foreach ($statuses as $status) {
            $datasets[] = [
                'label' => ucfirst($status),
                'data' => $analytics->pluck($status),
            ];
        }
        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }

    /**
     * API: Get past attendance records (filter by class, student, date, status)
     */
    public function getPastRecords(Request $request)
    {
        $query = AttendanceRecord::query();
        if ($request->filled('class_id')) $query->where('class_id', $request->class_id);
        if ($request->filled('student_id')) $query->where('student_id', $request->student_id);
        if ($request->filled('date')) $query->where('date', $request->date);
        if ($request->filled('status')) $query->where('status', $request->status);
        $records = $query->with(['student', 'class', 'markedBy'])->orderBy('date', 'desc')->paginate(30);
        return response()->json($records);
    }

    /**
     * API: Update a past attendance record (with audit log)
     */
    public function updatePastRecord(Request $request, $id)
    {
        $record = AttendanceRecord::findOrFail($id);
        // Permission check (admin/principal/HR only)
        if (!auth()->user()->hasRole(['admin', 'principal', 'hr'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $data = $request->validate([
            'status' => 'required|in:present,absent,late,excused,sick,on_leave,suspended',
            'remarks' => 'nullable|string|max:255',
        ]);
        $before = $record->only(['status', 'remarks']);
        $record->update($data);
        AttendanceAuditLog::create([
            'attendance_record_id' => $record->id,
            'user_id' => auth()->id(),
            'action' => 'updated',
            'before' => $before,
            'after' => $data,
        ]);
        return response()->json($record);
    }

    /**
     * API: Get audit log for an attendance record
     */
    public function getAuditLog($id)
    {
        $logs = AttendanceAuditLog::where('attendance_record_id', $id)->with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($logs);
    }
} 