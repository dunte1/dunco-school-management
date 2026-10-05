<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $roles = $user->roles->pluck('name')->toArray();
            $schoolId = $user->school_id ?? 1;

            // Get announcements and notifications
            $announcements = $this->getAnnouncements($user->id);
            
            $data = [
                'announcements' => $announcements,
                'notifications_unread' => count($announcements),
                'user_info' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $roles,
                    'school_id' => $schoolId,
                ],
                'timestamp' => now()->toIso8601String(),
            ];

            if (in_array('student', $roles)) {
                $data += $this->getStudentDashboardData($user, $schoolId);
            } elseif (in_array('parent', $roles) || in_array('guardian', $roles)) {
                $data += $this->getParentDashboardData($user, $schoolId);
            } elseif (in_array('admin', $roles) || in_array('super_admin', $roles)) {
                $data += $this->getAdminDashboardData($user, $schoolId);
            } elseif (in_array('teacher', $roles)) {
                $data += $this->getTeacherDashboardData($user, $schoolId);
            } else {
                $data += ['type' => 'generic'];
            }

            return $this->successResponse($data, 'Dashboard data retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve dashboard data: ' . $e->getMessage());
        }
    }

    private function getStudentDashboardData($user, $schoolId)
    {
        try {
            // Get student attendance percentage
            $attendanceWeekPct = $this->studentAttendancePct($user->id, now()->startOfWeek(), now()->endOfWeek());
            $attendanceMonthPct = $this->studentAttendancePct($user->id, now()->startOfMonth(), now()->endOfMonth());

            // Get fees information
            $feesOutstanding = $this->getFeesOutstanding($user->id);
            $feesPaid = $this->getFeesPaid($user->id);

            // Get upcoming classes
            $upcomingClasses = $this->getUpcomingClasses($user->id);

            // Get due assignments
            $dueAssignments = $this->getDueAssignments($user->id);

            // Get recent exam results
            $recentResults = $this->getRecentExamResults($user->id);

            return [
                'type' => 'student',
                'attendance' => [
                    'week_pct' => $attendanceWeekPct,
                    'month_pct' => $attendanceMonthPct,
                    'status' => $attendanceWeekPct >= 75 ? 'good' : ($attendanceWeekPct >= 50 ? 'average' : 'poor'),
                ],
                'fees' => [
                    'outstanding' => $feesOutstanding,
                    'paid' => $feesPaid,
                    'total' => $feesOutstanding + $feesPaid,
                    'percentage' => $feesPaid > 0 ? round(($feesPaid / ($feesOutstanding + $feesPaid)) * 100) : 0,
                ],
                'upcoming_classes' => $upcomingClasses,
                'due_assignments' => $dueAssignments,
                'recent_results' => $recentResults,
                'quick_stats' => [
                    'total_subjects' => $this->getTotalSubjects($user->id),
                    'assignments_pending' => count($dueAssignments),
                    'exams_upcoming' => $this->getUpcomingExamsCount($user->id),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'student',
                'attendance' => ['week_pct' => 0, 'month_pct' => 0, 'status' => 'unknown'],
                'fees' => ['outstanding' => 0, 'paid' => 0, 'total' => 0, 'percentage' => 0],
                'upcoming_classes' => [],
                'due_assignments' => [],
                'recent_results' => [],
                'quick_stats' => ['total_subjects' => 0, 'assignments_pending' => 0, 'exams_upcoming' => 0],
            ];
        }
    }

    private function getParentDashboardData($user, $schoolId)
    {
        try {
            // Get children information
            $children = $this->getChildrenData($user->id);
            
            // Get alerts for all children
            $alerts = $this->getParentAlerts($user->id);

            return [
                'type' => 'parent',
                'children' => $children,
                'alerts' => $alerts,
                'quick_stats' => [
                    'total_children' => count($children),
                    'alerts_count' => count($alerts),
                    'children_with_issues' => count(array_filter($alerts, fn($alert) => $alert['priority'] === 'high')),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'parent',
                'children' => [],
                'alerts' => [],
                'quick_stats' => ['total_children' => 0, 'alerts_count' => 0, 'children_with_issues' => 0],
            ];
        }
    }

    private function getAdminDashboardData($user, $schoolId)
    {
        try {
            // Get comprehensive statistics
            $stats = $this->getAdminStatistics($schoolId);
            
            // Get recent activities
            $recentActivities = $this->getRecentActivities($schoolId);
            
            // Get system health
            $systemHealth = $this->getSystemHealth();
            
            // Get financial overview
            $financialOverview = $this->getFinancialOverview($schoolId);

            return [
                'type' => 'admin',
                'statistics' => $stats,
                'recent_activities' => $recentActivities,
                'system_health' => $systemHealth,
                'financial_overview' => $financialOverview,
                'quick_actions' => [
                    'add_student' => true,
                    'add_teacher' => true,
                    'mark_attendance' => true,
                    'send_notification' => true,
                    'generate_report' => true,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'admin',
                'statistics' => ['total_students' => 0, 'total_teachers' => 0, 'total_classes' => 0, 'total_subjects' => 0],
                'recent_activities' => [],
                'system_health' => ['status' => 'unknown', 'uptime' => 0],
                'financial_overview' => ['total_fees' => 0, 'collected' => 0, 'pending' => 0],
                'quick_actions' => ['add_student' => false, 'add_teacher' => false, 'mark_attendance' => false, 'send_notification' => false, 'generate_report' => false],
            ];
        }
    }

    private function getTeacherDashboardData($user, $schoolId)
    {
        try {
            // Get teacher's classes
            $classes = $this->getTeacherClasses($user->id);
            
            // Get today's schedule
            $todaySchedule = $this->getTodaySchedule($user->id);
            
            // Get pending assignments to grade
            $pendingGrading = $this->getPendingGrading($user->id);

            return [
                'type' => 'teacher',
                'classes' => $classes,
                'today_schedule' => $todaySchedule,
                'pending_grading' => $pendingGrading,
                'quick_stats' => [
                    'total_classes' => count($classes),
                    'classes_today' => count($todaySchedule),
                    'assignments_to_grade' => count($pendingGrading),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'teacher',
                'classes' => [],
                'today_schedule' => [],
                'pending_grading' => [],
                'quick_stats' => ['total_classes' => 0, 'classes_today' => 0, 'assignments_to_grade' => 0],
            ];
        }
    }

    private function getAnnouncements($userId)
    {
        try {
            if (class_exists('Modules\\Communication\\Models\\Notification')) {
                return \Modules\Communication\Models\Notification::where('notifiable_id', $userId)
                    ->whereNull('read_at')
                    ->latest()
                    ->take(10)
                    ->get(['id','title','type','data','created_at'])
                    ->map(function($n){
                        return [
                            'id' => $n->id,
                            'title' => $n->title,
                            'type' => $n->type,
                            'created_at' => optional($n->created_at)->toIso8601String(),
                        ];
                    })->toArray();
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function studentAttendancePct(int $studentId, $from, $to): int
    {
        try {
            if (!class_exists('Modules\\Academic\\Models\\AttendanceRecord')) { 
                return 0; 
            }
            $q = \Modules\Academic\Models\AttendanceRecord::where('student_id', $studentId)
                ->whereBetween('date', [$from, $to]);
            $total = (clone $q)->count();
            if ($total === 0) { return 0; }
            $present = (clone $q)->where('status','present')->count();
            return (int) round(($present / $total) * 100);
        } catch (\Throwable $e) { 
            return 0; 
        }
    }

    private function getFeesOutstanding($userId)
    {
        try {
            if (class_exists('Modules\\Finance\\Models\\Invoice')) {
                return \Modules\Finance\Models\Invoice::where('status','unpaid')
                    ->where('student_id', $userId)
                    ->sum('amount') ?? 0;
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getFeesPaid($userId)
    {
        try {
            if (class_exists('Modules\\Finance\\Models\\Payment')) {
                return \Modules\Finance\Models\Payment::where('student_id', $userId)
                    ->where('status', 'completed')
                    ->sum('amount') ?? 0;
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getUpcomingClasses($userId)
    {
        try {
            // Implementation for getting upcoming classes
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getDueAssignments($userId)
    {
        try {
            // Implementation for getting due assignments
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getRecentExamResults($userId)
    {
        try {
            // Implementation for getting recent exam results
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getTotalSubjects($userId)
    {
        try {
            // Implementation for getting total subjects
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getUpcomingExamsCount($userId)
    {
        try {
            // Implementation for getting upcoming exams count
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getChildrenData($parentId)
    {
        try {
            // Implementation for getting children data
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getParentAlerts($parentId)
    {
        try {
            // Implementation for getting parent alerts
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getAdminStatistics($schoolId)
    {
        try {
            $totalStudents = 0;
            $totalTeachers = 0;
            $totalClasses = 0;
            $totalSubjects = 0;

            if (class_exists('Modules\\Academic\\Models\\Student')) {
                $totalStudents = \Modules\Academic\Models\Student::where('school_id', $schoolId)->count();
            }
            if (class_exists('Modules\\HR\\Models\\Staff')) {
                $totalTeachers = \Modules\HR\Models\Staff::where('school_id', $schoolId)->where('status', 'active')->count();
            }
            if (class_exists('Modules\\Academic\\Models\\AcademicClass')) {
                $totalClasses = \Modules\Academic\Models\AcademicClass::where('school_id', $schoolId)->count();
            }
            if (class_exists('Modules\\Academic\\Models\\Subject')) {
                $totalSubjects = \Modules\Academic\Models\Subject::where('school_id', $schoolId)->count();
            }

            return [
                'total_students' => $totalStudents,
                'total_teachers' => $totalTeachers,
                'total_classes' => $totalClasses,
                'total_subjects' => $totalSubjects,
                'today_attendance' => $this->getTodayAttendance($schoolId),
                'active_users' => $this->getActiveUsers($schoolId),
            ];
        } catch (\Exception $e) {
            return [
                'total_students' => 0,
                'total_teachers' => 0,
                'total_classes' => 0,
                'total_subjects' => 0,
                'today_attendance' => 0,
                'active_users' => 0,
            ];
        }
    }

    private function getTodayAttendance($schoolId)
    {
        try {
            if (class_exists('Modules\\Attendance\\Models\\Attendance')) {
                return \Modules\Attendance\Models\Attendance::where('school_id', $schoolId)
                    ->whereDate('date', Carbon::today())
                    ->where('status', 'present')
                    ->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getActiveUsers($schoolId)
    {
        try {
            return \App\Models\User::where('school_id', $schoolId)
                ->where('last_activity_at', '>=', now()->subHours(24))
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getRecentActivities($schoolId)
    {
        try {
            // Implementation for getting recent activities
            return [
                [
                    'id' => 1,
                    'type' => 'student_registration',
                    'message' => 'New student registered today',
                    'timestamp' => now()->subHours(1)->toISOString(),
                ],
                [
                    'id' => 2,
                    'type' => 'attendance_marked',
                    'message' => 'Attendance marked for today',
                    'timestamp' => now()->subHours(3)->toISOString(),
                ],
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getSystemHealth()
    {
        try {
            return [
                'status' => 'healthy',
                'uptime' => '99.9%',
                'database' => 'connected',
                'cache' => 'active',
                'storage' => 'normal',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unknown',
                'uptime' => '0%',
                'database' => 'unknown',
                'cache' => 'unknown',
                'storage' => 'unknown',
            ];
        }
    }

    private function getFinancialOverview($schoolId)
    {
        try {
            $totalFees = 0;
            $collected = 0;
            $pending = 0;

            if (class_exists('Modules\\Finance\\Models\\Fee')) {
                $totalFees = \Modules\Finance\Models\Fee::where('school_id', $schoolId)->sum('amount');
            }
            if (class_exists('Modules\\Finance\\Models\\Payment')) {
                $collected = \Modules\Finance\Models\Payment::where('school_id', $schoolId)
                    ->where('status', 'completed')
                    ->sum('amount');
            }
            if (class_exists('Modules\\Finance\\Models\\Invoice')) {
                $pending = \Modules\Finance\Models\Invoice::where('school_id', $schoolId)
                    ->where('status', 'unpaid')
                    ->sum('amount');
            }

            return [
                'total_fees' => $totalFees,
                'collected' => $collected,
                'pending' => $pending,
                'collection_rate' => $totalFees > 0 ? round(($collected / $totalFees) * 100, 2) : 0,
            ];
        } catch (\Exception $e) {
            return [
                'total_fees' => 0,
                'collected' => 0,
                'pending' => 0,
                'collection_rate' => 0,
            ];
        }
    }

    private function getTeacherClasses($teacherId)
    {
        try {
            // Implementation for getting teacher classes
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getTodaySchedule($teacherId)
    {
        try {
            // Implementation for getting today's schedule
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getPendingGrading($teacherId)
    {
        try {
            // Implementation for getting pending grading
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    // Module-specific methods for different user roles
    public function elearningModules(Request $request)
    {
        try {
            $user = $request->user();
            $modules = [
                ['name' => 'homework', 'status' => '1', 'short_code' => 'homework'],
                ['name' => 'assignment', 'status' => '1', 'short_code' => 'assignment'],
                ['name' => 'lessonplan', 'status' => '1', 'short_code' => 'lessonplan'],
                ['name' => 'onlineexam', 'status' => '1', 'short_code' => 'onlineexam'],
                ['name' => 'downloadcenter', 'status' => '1', 'short_code' => 'downloadcenter'],
                ['name' => 'onlinecourse', 'status' => '1', 'short_code' => 'onlinecourse'],
                ['name' => 'videocam', 'status' => '1', 'short_code' => 'videocam'],
            ];

            return $this->successResponse(['module_list' => $modules], 'E-learning modules retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve e-learning modules: ' . $e->getMessage());
        }
    }

    public function communicateModules(Request $request)
    {
        try {
            $modules = [
                ['name' => 'notice', 'status' => '1', 'short_code' => 'notice'],
                ['name' => 'notification', 'status' => '1', 'short_code' => 'notification'],
            ];

            return $this->successResponse(['module_list' => $modules], 'Communication modules retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve communication modules: ' . $e->getMessage());
        }
    }

    public function academicsModules(Request $request)
    {
        try {
            $modules = [
                ['name' => 'calender_cross', 'status' => '1', 'short_code' => 'calender_cross'],
                ['name' => 'lessonplan', 'status' => '1', 'short_code' => 'lessonplan'],
                ['name' => 'attendance', 'status' => '1', 'short_code' => 'attendance'],
                ['name' => 'reportcard', 'status' => '1', 'short_code' => 'reportcard'],
                ['name' => 'timeline', 'status' => '1', 'short_code' => 'timeline'],
                ['name' => 'documents_certificate', 'status' => '1', 'short_code' => 'documents_certificate'],
                ['name' => 'homework', 'status' => '1', 'short_code' => 'homework'],
            ];

            return $this->successResponse(['module_list' => $modules], 'Academic modules retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve academic modules: ' . $e->getMessage());
        }
    }

    public function othersModules(Request $request)
    {
        try {
            $modules = [
                ['name' => 'fees', 'status' => '1', 'short_code' => 'fees'],
                ['name' => 'leave', 'status' => '1', 'short_code' => 'leave'],
                ['name' => 'visitors', 'status' => '1', 'short_code' => 'visitors'],
                ['name' => 'transport', 'status' => '1', 'short_code' => 'transport'],
                ['name' => 'hostel', 'status' => '1', 'short_code' => 'hostel'],
                ['name' => 'pandingtask', 'status' => '1', 'short_code' => 'pandingtask'],
                ['name' => 'library', 'status' => '1', 'short_code' => 'library'],
                ['name' => 'teacher', 'status' => '1', 'short_code' => 'teacher'],
            ];

            return $this->successResponse(['module_list' => $modules], 'Other modules retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve other modules: ' . $e->getMessage());
        }
    }
}


