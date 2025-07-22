<?php

namespace Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        return view('attendance::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attendance::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('attendance::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('attendance::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

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
                // Assume parent_id and parent is Notifiable
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

        // Summary stats
        $total = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent = $records->where('status', 'absent')->count();
        $late = $records->where('status', 'late')->count();
        $excused = $records->where('status', 'excused')->count();

        // Attendance percentage per student
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
            'criteria' => 'required|string', // e.g., 'absent', 'late', 'all', 'custom'
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
        // Log bulk notification
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
        // Log bulk notification
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
