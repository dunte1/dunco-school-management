<?php

namespace Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Modules\HR\Models\StaffAttendanceRecord;

class DashboardController extends Controller
{
    /**
     * Get attendance dashboard stats.
     * Filters: date, class_id, department_id, teacher_id
     */
    public function stats(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $classId = $request->input('class_id');
        $departmentId = $request->input('department_id');
        $teacherId = $request->input('teacher_id');

        // Total students (filtered)
        $studentsQuery = DB::table('academic_students')->where('is_active', 1);
        if ($classId) $studentsQuery->where('class_id', $classId);
        if ($departmentId) $studentsQuery->where('department_id', $departmentId);
        if ($teacherId) $studentsQuery->where('teacher_id', $teacherId);
        $totalStudents = $studentsQuery->count();

        // Attendance records for the day (filtered)
        $attendanceQuery = DB::table('academic_attendance_records')->where('date', $date);
        if ($classId) $attendanceQuery->where('class_id', $classId);
        if ($departmentId) $attendanceQuery->where('department_id', $departmentId);
        if ($teacherId) $attendanceQuery->where('marked_by', $teacherId);
        $attendance = $attendanceQuery->get();

        $present = $attendance->where('status', 'present')->count();
        $absent = $attendance->where('status', 'absent')->count();
        $late = $attendance->where('status', 'late')->count();

        // Defaulters: students with attendance below threshold (e.g., <75%)
        $minPercent = 75;
        $defaulters = []; // TODO: Implement logic for defaulters

        return response()->json([
            'total_students' => $totalStudents,
            'present_today' => $present,
            'absent_today' => $absent,
            'late_arrivals' => $late,
            'defaulters' => $defaulters,
            'filters' => [
                'date' => $date,
                'class_id' => $classId,
                'department_id' => $departmentId,
                'teacher_id' => $teacherId,
            ],
        ]);
    }

    /**
     * Get attendance trend data for the past 7 days.
     */
    public function trendData(Request $request)
    {
        $classId = $request->input('class_id');
        $today = \Carbon\Carbon::today();
        $labels = [];
        $present = [];
        $absent = [];
        $late = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->toDateString();
            $labels[] = $date;
            $query = \DB::table('academic_attendance_records')->where('date', $date);
            if ($classId) $query->where('class_id', $classId);
            $records = $query->get();
            $present[] = $records->where('status', 'present')->count();
            $absent[] = $records->where('status', 'absent')->count();
            $late[] = $records->where('status', 'late')->count();
        }
        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $present,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'fill' => true,
                ],
                [
                    'label' => 'Absent',
                    'data' => $absent,
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'fill' => true,
                ],
                [
                    'label' => 'Late',
                    'data' => $late,
                    'borderColor' => 'rgba(255, 205, 86, 1)',
                    'backgroundColor' => 'rgba(255, 205, 86, 0.2)',
                    'fill' => true,
                ]
            ]
        ]);
    }

    /**
     * Get staff attendance summary for today.
     */
    public function staffSummary(Request $request)
    {
        $date = $request->input('date', \Carbon\Carbon::today()->toDateString());
        $statuses = ['present', 'absent', 'late', 'on_leave', 'sick', 'excused', 'suspended'];
        $summary = [];
        foreach ($statuses as $status) {
            $summary[$status] = StaffAttendanceRecord::where('date', $date)->where('status', $status)->count();
        }
        $summary['total'] = StaffAttendanceRecord::where('date', $date)->count();
        return response()->json($summary);
    }

    /**
     * Get staff attendance trend for the past 7 days.
     */
    public function staffTrend(Request $request)
    {
        $today = \Carbon\Carbon::today();
        $labels = [];
        $trend = [
            'present' => [],
            'absent' => [],
            'late' => [],
            'on_leave' => [],
            'sick' => [],
            'excused' => [],
            'suspended' => [],
        ];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->toDateString();
            $labels[] = $date;
            foreach (array_keys($trend) as $status) {
                $trend[$status][] = StaffAttendanceRecord::where('date', $date)->where('status', $status)->count();
            }
        }
        $datasets = [];
        $colors = [
            'present' => 'rgba(75, 192, 192, 1)',
            'absent' => 'rgba(255, 99, 132, 1)',
            'late' => 'rgba(255, 205, 86, 1)',
            'on_leave' => 'rgba(54, 162, 235, 1)',
            'sick' => 'rgba(153, 102, 255, 1)',
            'excused' => 'rgba(201, 203, 207, 1)',
            'suspended' => 'rgba(100, 100, 100, 1)',
        ];
        foreach ($trend as $status => $data) {
            $datasets[] = [
                'label' => ucfirst(str_replace('_', ' ', $status)),
                'data' => $data,
                'borderColor' => $colors[$status],
                'backgroundColor' => $colors[$status],
                'fill' => false,
            ];
        }
        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }
} 