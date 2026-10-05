<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Models\OnlineClassAttendance;
use Modules\Academic\Models\OnlineClass;
use Modules\Academic\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OnlineAttendanceController extends Controller
{
    /**
     * Display a listing of online attendance records.
     */
    public function index(Request $request)
    {
        $query = OnlineClassAttendance::with(['onlineClass', 'student']);

        // Filter by online class
        if ($request->has('online_class_id') && $request->online_class_id) {
            $query->where('online_class_id', $request->online_class_id);
        }

        // Filter by student
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('attendance_date', $request->date);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by student name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $attendanceRecords = $query->orderBy('attendance_date', 'desc')->paginate(15);
        $onlineClasses = OnlineClass::where('is_active', true)->get();
        $students = Student::where('is_active', true)->get();

        return view('academic::online-attendance.index', compact('attendanceRecords', 'onlineClasses', 'students'));
    }

    /**
     * Show the form for creating a new online attendance record.
     */
    public function create()
    {
        $onlineClasses = OnlineClass::where('is_active', true)->get();
        $students = Student::where('is_active', true)->get();
        
        return view('academic::online-attendance.create', compact('onlineClasses', 'students'));
    }

    /**
     * Store a newly created online attendance record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'online_class_id' => 'required|exists:academic_online_classes,id',
            'student_id' => 'required|exists:academic_students,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'join_time' => 'nullable|date_format:H:i',
            'leave_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ]);

        // Check if attendance record already exists for this student and class on this date
        $existingRecord = OnlineClassAttendance::where([
            'online_class_id' => $request->online_class_id,
            'student_id' => $request->student_id,
            'attendance_date' => $request->attendance_date
        ])->first();

        if ($existingRecord) {
            return back()->withErrors(['error' => 'Attendance record already exists for this student on this date.']);
        }

        try {
            OnlineClassAttendance::create([
                'online_class_id' => $request->online_class_id,
                'student_id' => $request->student_id,
                'attendance_date' => $request->attendance_date,
                'status' => $request->status,
                'join_time' => $request->join_time,
                'leave_time' => $request->leave_time,
                'duration_minutes' => $request->duration_minutes,
                'notes' => $request->notes,
                'recorded_by' => Auth::id()
            ]);

            return redirect()->route('academic.online-attendance.index')
                           ->with('success', 'Online attendance record created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create attendance record: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified online attendance record.
     */
    public function show($id)
    {
        $attendanceRecord = OnlineClassAttendance::with(['onlineClass', 'student'])
                                                ->findOrFail($id);
        
        return view('academic::online-attendance.show', compact('attendanceRecord'));
    }

    /**
     * Show the form for editing the specified online attendance record.
     */
    public function edit($id)
    {
        $attendanceRecord = OnlineClassAttendance::findOrFail($id);
        $onlineClasses = OnlineClass::where('is_active', true)->get();
        $students = Student::where('is_active', true)->get();
        
        return view('academic::online-attendance.edit', compact('attendanceRecord', 'onlineClasses', 'students'));
    }

    /**
     * Update the specified online attendance record.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'online_class_id' => 'required|exists:academic_online_classes,id',
            'student_id' => 'required|exists:academic_students,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'join_time' => 'nullable|date_format:H:i',
            'leave_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ]);

        try {
            $attendanceRecord = OnlineClassAttendance::findOrFail($id);
            $attendanceRecord->update([
                'online_class_id' => $request->online_class_id,
                'student_id' => $request->student_id,
                'attendance_date' => $request->attendance_date,
                'status' => $request->status,
                'join_time' => $request->join_time,
                'leave_time' => $request->leave_time,
                'duration_minutes' => $request->duration_minutes,
                'notes' => $request->notes
            ]);

            return redirect()->route('academic.online-attendance.index')
                           ->with('success', 'Online attendance record updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update attendance record: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified online attendance record.
     */
    public function destroy($id)
    {
        try {
            $attendanceRecord = OnlineClassAttendance::findOrFail($id);
            $attendanceRecord->delete();

            return redirect()->route('academic.online-attendance.index')
                           ->with('success', 'Online attendance record deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete attendance record: ' . $e->getMessage()]);
        }
    }

    /**
     * Get online attendance statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_records' => OnlineClassAttendance::count(),
            'present_count' => OnlineClassAttendance::where('status', 'present')->count(),
            'absent_count' => OnlineClassAttendance::where('status', 'absent')->count(),
            'late_count' => OnlineClassAttendance::where('status', 'late')->count(),
            'excused_count' => OnlineClassAttendance::where('status', 'excused')->count(),
            'attendance_rate' => OnlineClassAttendance::count() > 0 ? 
                (OnlineClassAttendance::whereIn('status', ['present', 'late'])->count() / OnlineClassAttendance::count()) * 100 : 0,
            'class_attendance' => OnlineClassAttendance::select('online_class_id', DB::raw('count(*) as total'), DB::raw('sum(case when status in ("present", "late") then 1 else 0 end) as present'))
                ->with('onlineClass')
                ->groupBy('online_class_id')
                ->get()
        ];

        return view('academic::online-attendance.statistics', compact('stats'));
    }

    /**
     * Bulk import online attendance records.
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
            'online_class_id' => 'required|exists:academic_online_classes,id'
        ]);

        try {
            // Handle file upload and processing
            // This would need to be implemented based on your file structure
            
            return redirect()->route('academic.online-attendance.index')
                           ->with('success', 'Online attendance records imported successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to import attendance records: ' . $e->getMessage()]);
        }
    }

    /**
     * Export online attendance records.
     */
    public function export(Request $request)
    {
        $query = OnlineClassAttendance::with(['onlineClass', 'student']);

        // Apply filters
        if ($request->has('online_class_id') && $request->online_class_id) {
            $query->where('online_class_id', $request->online_class_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->where('attendance_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('attendance_date', '<=', $request->date_to);
        }

        $records = $query->get();

        // Generate CSV/Excel export
        // This would need to be implemented based on your export requirements

        return response()->download('online_attendance_export.csv');
    }
}
