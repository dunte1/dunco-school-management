<?php

namespace Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Attendance\Notifications\AttendanceStatusNotification;
use Modules\Attendance\Notifications\AttendanceSmsNotification;
use Modules\Attendance\App\Exports\AttendanceReportExport;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = \DB::table('academic_attendance_records')
            ->join('academic_students', 'academic_attendance_records.student_id', '=', 'academic_students.id')
            ->select('academic_attendance_records.*', 'academic_students.first_name', 'academic_students.last_name')
            ->orderByDesc('date')
            ->limit(100)
            ->get();

        return view('attendance::index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = \DB::table('academic_classes')->select('id', 'name')->get();
        return view('attendance::create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|integer',
                'class_id' => 'required|integer',
                'date' => 'required|date',
                'status' => 'required|in:present,absent,late,excused',
                'remarks' => 'nullable|string|max:500',
            ]);

            \DB::table('academic_attendance_records')->updateOrInsert(
                [
                    'student_id' => $validated['student_id'],
                    'class_id' => $validated['class_id'],
                    'date' => $validated['date'],
                ],
                [
                    'status' => $validated['status'],
                    'remarks' => $validated['remarks'] ?? null,
                    'marked_by' => $request->user()->id,
                    'is_active' => true,
                    'updated_at' => now(),
                ]
            );

            return redirect()->back()->with('success', 'Attendance recorded successfully.');
        } catch (\Exception $e) {
            Log::error('AttendanceController: Failed to store attendance - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to record attendance: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $record = \DB::table('academic_attendance_records')
            ->join('academic_students', 'academic_attendance_records.student_id', '=', 'academic_students.id')
            ->select('academic_attendance_records.*', 'academic_students.first_name', 'academic_students.last_name')
            ->where('academic_attendance_records.id', $id)
            ->first();

        return view('attendance::show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $record = \DB::table('academic_attendance_records')->where('id', $id)->first();
        return view('attendance::edit', compact('record'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $record = \DB::table('academic_attendance_records')->where('id', $id)->first();
            if (!$record) {
                return redirect()->back()->with('error', 'Attendance record not found.');
            }

            $validated = $request->validate([
                'status' => 'required|in:present,absent,late,excused',
                'remarks' => 'nullable|string|max:500',
            ]);

            $validated['updated_at'] = now();

            \DB::table('academic_attendance_records')->where('id', $id)->update($validated);

            return redirect()->back()->with('success', 'Attendance updated successfully.');
        } catch (\Exception $e) {
            Log::error('AttendanceController: Failed to update attendance - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update attendance: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $record = \DB::table('academic_attendance_records')->where('id', $id)->first();
            if (!$record) {
                return redirect()->back()->with('error', 'Attendance record not found.');
            }

            \DB::table('academic_attendance_records')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Attendance record deleted successfully.');
        } catch (\Exception $e) {
            Log::error('AttendanceController: Failed to delete attendance - ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete attendance: ' . $e->getMessage());
        }
    }

    /**
     * Show the attendance dashboard page.
     */
    public function dashboard()
    {
        return view('modules.attendance.dashboard');
    }

    /**
     * Show the attendance marking interface.
     */
    public function mark()
    {
        return view('modules.attendance.mark');
    }

    /**
     * Show the attendance reports interface.
     */
    public function reports()
    {
        return view('modules.attendance.reports');
    }

    /**
     * Show the attendance settings interface.
     */
    public function settings()
    {
        return view('modules.attendance.settings');
    }

    /**
     * Show past attendance records.
     */
    public function pastRecords(Request $request)
    {
        $query = \DB::table('academic_attendance_records')
            ->join('academic_students', 'academic_attendance_records.student_id', '=', 'academic_students.id')
            ->select(
                'academic_attendance_records.*',
                'academic_students.first_name',
                'academic_students.last_name'
            );

        if ($request->filled('date')) {
            $query->where('academic_attendance_records.date', $request->date);
        }
        if ($request->filled('class_id')) {
            $query->where('academic_attendance_records.class_id', $request->class_id);
        }
        if ($request->filled('status')) {
            $query->where('academic_attendance_records.status', $request->status);
        }

        $records = $query->orderByDesc('date')->paginate(50);
        $classes = \DB::table('academic_classes')->select('id', 'name')->get();

        return view('attendance::past-records', compact('records', 'classes'));
    }

    /**
     * Show session templates management.
     */
    public function sessionTemplates()
    {
        $templates = \DB::table('attendance_session_templates')->orderByDesc('created_at')->paginate(20);
        return view('attendance::session-templates', compact('templates'));
    }

    /**
     * Show biometric attendance logs.
     */
    public function biometricLogs(Request $request)
    {
        $query = \DB::table('biometric_attendance_logs')
            ->join('academic_students', 'biometric_attendance_logs.student_id', '=', 'academic_students.id', 'left')
            ->select('biometric_attendance_logs.*', 'academic_students.first_name', 'academic_students.last_name');

        if ($request->filled('date')) {
            $query->where('biometric_attendance_logs.date', $request->date);
        }
        if ($request->filled('student_id')) {
            $query->where('biometric_attendance_logs.student_id', $request->student_id);
        }

        $logs = $query->orderByDesc('created_at')->paginate(50);
        return view('attendance::biometric-logs', compact('logs'));
    }

    /**
     * Show QR code attendance logs.
     */
    public function qrLogs(Request $request)
    {
        $query = \DB::table('qr_attendance_logs')
            ->join('academic_students', 'qr_attendance_logs.student_id', '=', 'academic_students.id', 'left')
            ->select('qr_attendance_logs.*', 'academic_students.first_name', 'academic_students.last_name');

        if ($request->filled('date')) {
            $query->where('qr_attendance_logs.date', $request->date);
        }

        $logs = $query->orderByDesc('created_at')->paginate(50);
        return view('attendance::qr-logs', compact('logs'));
    }

    /**
     * Show face recognition attendance logs.
     */
    public function faceLogs(Request $request)
    {
        $query = \DB::table('face_attendance_logs')
            ->join('academic_students', 'face_attendance_logs.student_id', '=', 'academic_students.id', 'left')
            ->select('face_attendance_logs.*', 'academic_students.first_name', 'academic_students.last_name');

        if ($request->filled('date')) {
            $query->where('face_attendance_logs.date', $request->date);
        }

        $logs = $query->orderByDesc('created_at')->paginate(50);
        return view('attendance::face-logs', compact('logs'));
    }

    /**
     * Show acknowledgment logs.
     */
    public function acknowledgmentLogs(Request $request)
    {
        $query = \DB::table('attendance_acknowledgment_logs')
            ->join('academic_students', 'attendance_acknowledgment_logs.student_id', '=', 'academic_students.id', 'left')
            ->join('users', 'attendance_acknowledgment_logs.acknowledged_by', '=', 'users.id', 'left')
            ->select(
                'attendance_acknowledgment_logs.*',
                'academic_students.first_name',
                'academic_students.last_name',
                'users.name as acknowledged_by_name'
            );

        if ($request->filled('date')) {
            $query->where('attendance_acknowledgment_logs.date', $request->date);
        }

        $logs = $query->orderByDesc('created_at')->paginate(50);
        return view('attendance::acknowledgment-logs', compact('logs'));
    }

    /**
     * API: Get all classes.
     */
    public function getClasses()
    {
        $classes = \DB::table('academic_classes')->select('id', 'name')->get();
        return response()->json($classes);
    }

    /**
     * API: Get subjects by class.
     */
    public function getSubjects(Request $request)
    {
        $classId = $request->input('class_id');
        $subjects = \DB::table('subjects')
            ->select('id', 'name')
            ->when($classId, function($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->get();
        return response()->json($subjects);
    }

    /**
     * API: Get sessions by class/date.
     */
    public function getSessions(Request $request)
    {
        $classId = $request->input('class_id');
        $date = $request->input('date');
        $sessions = \DB::table('attendance_sessions')
            ->select('id', 'session_name', 'start_time', 'end_time')
            ->when($classId, function($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->when($date, function($q) use ($date) {
                $q->where('date', $date);
            })
            ->get();
        return response()->json($sessions);
    }

    /**
     * API: Get students by class.
     */
    public function getStudents(Request $request)
    {
        $classId = $request->input('class_id');
        $students = \DB::table('academic_students')
            ->select('id', 'first_name', 'last_name')
            ->when($classId, function($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->get();
        return response()->json($students);
    }

    /**
     * Store attendance records for a class/session/date.
     */
    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|integer',
            'subject_id' => 'nullable|integer',
            'date' => 'required|date',
            'session_id' => 'nullable|integer',
            'status' => 'required|array',
            'remarks' => 'nullable|array',
        ]);
        $classId = $validated['class_id'];
        $subjectId = $validated['subject_id'] ?? null;
        $date = $validated['date'];
        $sessionId = $validated['session_id'] ?? null;
        $statuses = $validated['status'];
        $remarks = $validated['remarks'] ?? [];
        $userId = $request->user()->id;

        foreach ($statuses as $studentId => $status) {
            \DB::table('academic_attendance_records')->updateOrInsert(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'date' => $date,
                ],
                [
                    'status' => $status,
                    'remarks' => $remarks[$studentId] ?? null,
                    'marked_by' => $userId,
                    'is_active' => true,
                    'updated_at' => now(),
                ]
            );
            // Send notification if absent or late
            if (in_array($status, ['absent', 'late'])) {
                $student = \DB::table('academic_students')->where('id', $studentId)->first();
                if ($student && isset($student->parent_id)) {
                    $parent = \App\Models\User::find($student->parent_id);
                    if ($parent) {
                        $parent->notify(new AttendanceStatusNotification($student, $status, $date));
                    }
                }
            }
        }
        return response()->json(['success' => true, 'message' => 'Attendance saved successfully.']);
    }

    /**
     * API: Get attendance report with filters.
     */
    public function getReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $classId = $request->input('class_id');
        $studentId = $request->input('student_id');
        $subjectId = $request->input('subject_id');

        $query = \DB::table('academic_attendance_records')
            ->join('academic_students', 'academic_attendance_records.student_id', '=', 'academic_students.id')
            ->select(
                'academic_attendance_records.*',
                'academic_students.first_name',
                'academic_students.last_name',
                'academic_students.class_id'
            );
        if ($startDate) $query->where('date', '>=', $startDate);
        if ($endDate) $query->where('date', '<=', $endDate);
        if ($classId) $query->where('academic_attendance_records.class_id', $classId);
        if ($studentId) $query->where('student_id', $studentId);
        if ($subjectId) $query->where('subject_id', $subjectId);

        $records = $query->get();

        $total = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent = $records->where('status', 'absent')->count();
        $late = $records->where('status', 'late')->count();
        $excused = $records->where('status', 'excused')->count();

        $percentages = [];
        $defaulters = [];
        $grouped = $records->groupBy('student_id');
        foreach ($grouped as $studentId => $recs) {
            $totalDays = $recs->count();
            $presentDays = $recs->where('status', 'present')->count();
            $percent = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
            $studentName = $recs->first()->first_name . ' ' . $recs->first()->last_name;
            $percentages[] = [
                'student_id' => $studentId,
                'name' => $studentName,
                'attendance_percent' => $percent,
            ];
            if ($percent < 75) {
                $defaulters[] = [
                    'student_id' => $studentId,
                    'name' => $studentName,
                    'attendance_percent' => $percent,
                ];
            }
        }

        return response()->json([
            'summary' => [
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
            ],
            'percentages' => $percentages,
            'defaulters' => $defaulters,
            'records' => $records,
        ]);
    }

    /**
     * Export attendance report to Excel.
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'class_id', 'student_id', 'subject_id', 'staff_id']);
        return (new AttendanceReportExport($filters))->download('attendance_report.xlsx');
    }

    /**
     * API: Get attendance settings.
     */
    public function getSettings()
    {
        $settings = \DB::table('attendance_settings')->first();
        return response()->json($settings);
    }

    /**
     * API: Save attendance settings.
     */
    public function saveSettings(Request $request)
    {
        $data = $request->validate([
            'default_marking_start' => 'nullable',
            'default_marking_end' => 'nullable',
            'late_threshold' => 'nullable',
            'min_attendance_percent' => 'nullable|integer',
            'allow_backdated_entries' => 'nullable|boolean',
            'teacher_can_backdate' => 'nullable|boolean',
            'notify_absent' => 'nullable|boolean',
            'notify_late' => 'nullable|boolean',
            'notify_channel' => 'nullable|string',
            'chronic_absent_threshold' => 'nullable|integer',
            'custom_message' => 'nullable|string',
        ]);
        $settings = \DB::table('attendance_settings')->first();
        if ($settings) {
            \DB::table('attendance_settings')->where('id', $settings->id)->update($data);
        } else {
            \DB::table('attendance_settings')->insert($data);
        }
        return response()->json(['success' => true, 'message' => 'Settings saved successfully.']);
    }

    /**
     * API: Send bulk notifications (email/SMS) to students/parents by criteria
     */
    public function sendBulkNotifications(Request $request)
    {
        if (!auth()->user()->hasRole(['admin', 'hr'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $data = $request->validate([
            'criteria' => 'required|string',
            'date' => 'required|date',
            'message' => 'required|string',
            'channel' => 'required|in:email,sms,both',
        ]);
        $query = \Modules\Academic\Models\AttendanceRecord::where('date', $data['date']);
        if ($data['criteria'] !== 'all') {
            $query->where('status', $data['criteria']);
        }
        $records = $query->with('student')->get();
        foreach ($records as $rec) {
            $user = $rec->student->user ?? null;
            if ($user) {
                if ($data['channel'] === 'email' || $data['channel'] === 'both') {
                    $user->notify(new \Modules\Attendance\Notifications\AttendanceStatusNotification($data['message']));
                }
                if ($data['channel'] === 'sms' || $data['channel'] === 'both') {
                    $user->notify(new AttendanceSmsNotification($data['message']));
                }
            }
        }
        \DB::table('attendance_audit_logs')->insert([
            'attendance_record_id' => null,
            'user_id' => auth()->id(),
            'action' => 'bulk_notification',
            'before' => null,
            'after' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Bulk notifications sent.']);
    }

    /**
     * API: Send X-days-absent alerts
     */
    public function sendXDaysAbsentAlerts(Request $request)
    {
        if (!auth()->user()->hasRole(['admin', 'hr'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $data = $request->validate([
            'days' => 'required|integer|min:2',
            'message' => 'required|string',
            'channel' => 'required|in:email,sms,both',
        ]);
        $students = \Modules\Academic\Models\Student::all();
        $today = now()->toDateString();
        foreach ($students as $student) {
            $absentCount = \Modules\Academic\Models\AttendanceRecord::where('student_id', $student->id)
                ->where('date', '<=', $today)
                ->orderBy('date', 'desc')
                ->take($data['days'])
                ->where('status', 'absent')
                ->count();
            if ($absentCount == $data['days']) {
                $user = $student->user ?? null;
                if ($user) {
                    if ($data['channel'] === 'email' || $data['channel'] === 'both') {
                        $user->notify(new \Modules\Attendance\Notifications\AttendanceStatusNotification($data['message']));
                    }
                    if ($data['channel'] === 'sms' || $data['channel'] === 'both') {
                        $user->notify(new AttendanceSmsNotification($data['message']));
                    }
                }
            }
        }
        \DB::table('attendance_audit_logs')->insert([
            'attendance_record_id' => null,
            'user_id' => auth()->id(),
            'action' => 'x_days_absent_alert',
            'before' => null,
            'after' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'X-days-absent alerts sent.']);
    }
}
