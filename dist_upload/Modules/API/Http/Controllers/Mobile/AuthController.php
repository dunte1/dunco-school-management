<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use Modules\Academic\Models\Student;
use Modules\HR\Models\Employee;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * User login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'device_token' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $credentials = $request->only('email', 'password');
            
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Account is deactivated'
                ], 403);
            }

            // Update device token if provided
            if ($request->device_token) {
                $user->device_token = $request->device_token;
                $user->save();
            }

            // Update last login
            $user->last_login_at = now();
            $user->save();

            // Create token
            $token = $user->createToken('mobile-app')->plainTextToken;
            $refreshToken = Str::random(60);
            
            // Store refresh token (you might want to store this in a separate table)
            $user->refresh_token = $refreshToken;
            $user->token_expires_at = now()->addDays(30);
            $user->save();

            // Get user role and additional data
            $userData = $this->getUserWithRoleData($user);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $userData,
                    'token' => $token,
                    'refresh_token' => $refreshToken,
                    'expires_at' => $user->token_expires_at->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile
     */
    public function getProfile(): JsonResponse
    {
        try {
            $user = Auth::user();
            $userData = $this->getUserWithRoleData($user);

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => $userData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            $user->name = $request->name;
            if ($request->phone) {
                $user->phone = $request->phone;
            }
            if ($request->avatar) {
                $user->avatar = $request->avatar;
            }
            
            $user->save();

            $userData = $this->getUserWithRoleData($user);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $userData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
            'new_password_confirmation' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Forgot password
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link sent to your email'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to send reset link'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset link: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to reset password'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout
     */
    public function logout(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Clear device token
            $user->device_token = null;
            $user->save();
            
            // Revoke all tokens
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh token
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('refresh_token', $request->refresh_token)
                       ->where('token_expires_at', '>', now())
                       ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired refresh token'
                ], 401);
            }

            // Create new token
            $token = $user->createToken('mobile-app')->plainTextToken;
            $refreshToken = Str::random(60);
            
            // Update refresh token
            $user->refresh_token = $refreshToken;
            $user->token_expires_at = now()->addDays(30);
            $user->save();

            $userData = $this->getUserWithRoleData($user);

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'data' => [
                    'user' => $userData,
                    'token' => $token,
                    'refresh_token' => $refreshToken,
                    'expires_at' => $user->token_expires_at->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard data based on user role
     */
    public function getDashboardData(): JsonResponse
    {
        try {
            $user = Auth::user();
            $dashboardData = [];

            switch ($user->role) {
                case 'student':
                    $dashboardData = $this->getStudentDashboardData($user);
                    break;
                case 'parent':
                    $dashboardData = $this->getParentDashboardData($user);
                    break;
                case 'teacher':
                    $dashboardData = $this->getTeacherDashboardData($user);
                    break;
                case 'admin':
                    $dashboardData = $this->getAdminDashboardData($user);
                    break;
                default:
                    $dashboardData = $this->getDefaultDashboardData($user);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dashboard data retrieved successfully',
                'data' => $dashboardData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user data with role-specific information
     */
    private function getUserWithRoleData(User $user): array
    {
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'avatar' => $user->avatar,
            'is_active' => $user->is_active,
            'last_login_at' => $user->last_login_at?->toISOString(),
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString()
        ];

        // Add role-specific data
        switch ($user->role) {
            case 'student':
                $student = Student::where('user_id', $user->id)->first();
                if ($student) {
                    $userData = array_merge($userData, [
                        'student_id' => $student->student_id,
                        'admission_number' => $student->admission_number,
                        'date_of_birth' => $student->date_of_birth?->toDateString(),
                        'gender' => $student->gender,
                        'address' => $student->address,
                        'parent_id' => $student->parent_id,
                        'class_id' => $student->class_id,
                        'section_id' => $student->section_id
                    ]);
                }
                break;
                
            case 'parent':
                $children = Student::where('parent_id', $user->id)->get();
                $userData['children'] = $children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'admission_number' => $child->admission_number,
                        'class_id' => $child->class_id,
                        'class_name' => $child->class->name ?? null,
                        'section_id' => $child->section_id,
                        'section_name' => $child->section->name ?? null,
                        'parent_id' => $child->parent_id,
                        'parent_name' => $child->parent->name ?? null,
                        'is_active' => $child->is_active
                    ];
                });
                break;
                
            case 'teacher':
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $userData = array_merge($userData, [
                        'employee_id' => $employee->employee_id,
                        'department' => $employee->department,
                        'qualification' => $employee->qualification,
                        'joining_date' => $employee->joining_date?->toDateString()
                    ]);
                }
                break;
        }

        return $userData;
    }

    /**
     * Get student dashboard data
     */
    private function getStudentDashboardData(User $user): array
    {
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return [];
        }

        return [
            'academic_info' => [
                'class' => $student->class->name ?? null,
                'section' => $student->section->name ?? null,
                'admission_number' => $student->admission_number
            ],
            'recent_grades' => [], // Will be populated by AcademicController
            'attendance_summary' => [], // Will be populated by AcademicController
            'upcoming_assignments' => [], // Will be populated by AcademicController
            'fee_summary' => [] // Will be populated by FinanceController
        ];
    }

    /**
     * Get parent dashboard data
     */
    private function getParentDashboardData(User $user): array
    {
        $children = Student::where('parent_id', $user->id)->get();
        
        return [
            'children' => $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'admission_number' => $child->admission_number,
                    'class' => $child->class->name ?? null,
                    'section' => $child->section->name ?? null
                ];
            }),
            'fee_summary' => [], // Will be populated by FinanceController
            'recent_activities' => [] // Will be populated by various controllers
        ];
    }

    /**
     * Get teacher dashboard data
     */
    private function getTeacherDashboardData(User $user): array
    {
        return [
            'classes_taught' => [], // Will be populated by AcademicController
            'recent_attendance' => [], // Will be populated by AcademicController
            'pending_assignments' => [], // Will be populated by AcademicController
            'student_count' => 0 // Will be populated by AcademicController
        ];
    }

    /**
     * Get admin dashboard data
     */
    private function getAdminDashboardData(User $user): array
    {
        return [
            'total_students' => Student::count(),
            'total_teachers' => Employee::where('role', 'teacher')->count(),
            'total_classes' => 0, // Will be populated by AcademicController
            'recent_activities' => [] // Will be populated by various controllers
        ];
    }

    /**
     * Get default dashboard data
     */
    private function getDefaultDashboardData(User $user): array
    {
        return [
            'welcome_message' => "Welcome to Dunco School Management System",
            'user_info' => [
                'name' => $user->name,
                'role' => $user->role
            ]
        ];
    }
} 