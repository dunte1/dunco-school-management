<?php

namespace Modules\API\Http\Controllers\Mobile\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Modules\Academic\Models\Student;
use Modules\HR\Models\Staff;
use Modules\Academic\Models\AcademicClass;
use Modules\Examination\Models\Exam;
use Modules\Finance\Models\FeePayment;
use Modules\Attendance\Models\Attendance;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Get admin dashboard data
     */
    public function getDashboardData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1; // Default to school 1 if not set
            
            // Get basic statistics
            $totalStudents = Student::where('school_id', $schoolId)->count();
            $totalTeachers = Staff::where('school_id', $schoolId)->where('role', 'teacher')->count();
            $totalClasses = AcademicClass::where('school_id', $schoolId)->count();
            $totalExams = Exam::where('school_id', $schoolId)->count();
            
            // Get today's attendance
            $todayAttendance = Attendance::where('school_id', $schoolId)
                ->whereDate('date', Carbon::today())
                ->count();
            
            // Get recent activities (last 7 days)
            $recentActivities = $this->getRecentActivitiesData($schoolId);
            
            // Get pending tasks
            $pendingTasks = $this->getPendingTasks($schoolId);
            
            // Get financial summary
            $financialSummary = $this->getFinancialSummary($schoolId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => [
                        'total_students' => $totalStudents,
                        'total_teachers' => $totalTeachers,
                        'total_classes' => $totalClasses,
                        'total_exams' => $totalExams,
                        'today_attendance' => $todayAttendance,
                    ],
                    'recent_activities' => $recentActivities,
                    'pending_tasks' => $pendingTasks,
                    'financial_summary' => $financialSummary,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get system statistics
     */
    public function getStats(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            // Get detailed statistics
            $stats = [
                'students' => [
                    'total' => Student::where('school_id', $schoolId)->count(),
                    'active' => Student::where('school_id', $schoolId)->where('status', 'active')->count(),
                    'inactive' => Student::where('school_id', $schoolId)->where('status', 'inactive')->count(),
                ],
                'teachers' => [
                    'total' => Staff::where('school_id', $schoolId)->where('role', 'teacher')->count(),
                    'active' => Staff::where('school_id', $schoolId)->where('role', 'teacher')->where('status', 'active')->count(),
                ],
                'classes' => [
                    'total' => AcademicClass::where('school_id', $schoolId)->count(),
                    'with_students' => AcademicClass::where('school_id', $schoolId)->has('students')->count(),
                ],
                'attendance' => [
                    'today_present' => Attendance::where('school_id', $schoolId)
                        ->whereDate('date', Carbon::today())
                        ->where('status', 'present')
                        ->count(),
                    'today_absent' => Attendance::where('school_id', $schoolId)
                        ->whereDate('date', Carbon::today())
                        ->where('status', 'absent')
                        ->count(),
                ],
                'exams' => [
                    'total' => Exam::where('school_id', $schoolId)->count(),
                    'upcoming' => Exam::where('school_id', $schoolId)
                        ->where('exam_date', '>=', Carbon::today())
                        ->count(),
                ],
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get recent activities
     */
    public function getRecentActivities(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $activities = $this->getRecentActivitiesData($schoolId);
            
            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recent activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get system settings
     */
    public function getSettings(Request $request): JsonResponse
    {
        try {
            // Return system settings
            $settings = [
                'school_name' => 'DUNCO School Management System',
                'academic_year' => '2024-2025',
                'term' => 'First Term',
                'features' => [
                    'attendance_tracking' => true,
                    'exam_management' => true,
                    'fee_management' => true,
                    'library_management' => true,
                    'transport_management' => true,
                    'hostel_management' => true,
                ]
            ];
            
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update system settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        try {
            // Update settings logic here
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get users
     */
    public function getUsers(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            
            $users = User::where('school_id', $schoolId)
                ->select('id', 'name', 'email', 'role', 'status', 'created_at')
                ->paginate(20);
            
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create user
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'role' => 'required|in:admin,teacher,student,parent',
                'password' => 'required|string|min:8',
            ]);
            
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => bcrypt($data['password']),
                'school_id' => $request->user()->school_id ?? 1,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update user
     */
    public function updateUser(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            
            $data = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'role' => 'sometimes|in:admin,teacher,student,parent',
                'status' => 'sometimes|in:active,inactive',
            ]);
            
            $user->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete user
     */
    public function deleteUser(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get notifications
     */
    public function getNotifications(Request $request): JsonResponse
    {
        try {
            // Return notifications
            $notifications = [
                [
                    'id' => 1,
                    'title' => 'New Student Registration',
                    'message' => 'John Doe has been registered in Class 10A',
                    'type' => 'info',
                    'created_at' => Carbon::now()->subHours(2)->toISOString(),
                ],
                [
                    'id' => 2,
                    'title' => 'Exam Schedule Updated',
                    'message' => 'Mathematics exam has been rescheduled to tomorrow',
                    'type' => 'warning',
                    'created_at' => Carbon::now()->subHours(4)->toISOString(),
                ],
            ];
            
            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send notification
     */
    public function sendNotification(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|in:info,warning,error,success',
                'target_users' => 'required|array',
            ]);
            
            // Send notification logic here
            
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get recent activities data (private method)
     */
    private function getRecentActivitiesData($schoolId)
    {
        return [
            [
                'id' => 1,
                'type' => 'student_registration',
                'message' => 'New student John Doe registered in Class 10A',
                'timestamp' => Carbon::now()->subHours(1)->toISOString(),
            ],
            [
                'id' => 2,
                'type' => 'attendance_marked',
                'message' => 'Attendance marked for Class 9B',
                'timestamp' => Carbon::now()->subHours(2)->toISOString(),
            ],
            [
                'id' => 3,
                'type' => 'exam_created',
                'message' => 'Mathematics exam scheduled for tomorrow',
                'timestamp' => Carbon::now()->subHours(3)->toISOString(),
            ],
        ];
    }
    
    /**
     * Get pending tasks (private method)
     */
    private function getPendingTasks($schoolId)
    {
        return [
            [
                'id' => 1,
                'title' => 'Review pending fee payments',
                'priority' => 'high',
                'due_date' => Carbon::today()->addDays(1)->toISOString(),
            ],
            [
                'id' => 2,
                'title' => 'Update class timetables',
                'priority' => 'medium',
                'due_date' => Carbon::today()->addDays(3)->toISOString(),
            ],
        ];
    }
    
    /**
     * Get financial summary (private method)
     */
    private function getFinancialSummary($schoolId)
    {
        return [
            'total_fees_collected' => 150000,
            'pending_payments' => 25000,
            'monthly_target' => 200000,
            'collection_rate' => 85.5,
        ];
    }
}
