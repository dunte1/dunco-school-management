<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    use ApiResponse;
    public function login(Request $request)
    {
        try {
            // Handle both JSON and form data
            $email = $request->input('email') ?? $request->input('username');
            $password = $request->input('password');

            if (!$email || !$password) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Email/Username and password are required'
                ], 400);
            }

            // Try to authenticate
            if (!Auth::attempt(['email' => $email, 'password' => $password])) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('mobile')->plainTextToken;

            // Determine user role/type
            $userType = $user->type ?? 'student';
            $isAdmin = $user->hasRole('admin') || $userType === 'admin';
            $isParent = $userType === 'parent';
            $isTeacher = $userType === 'teacher';
            $isStudent = $userType === 'student';

            // Build user data in the EXACT format Android app expects
            $userData = [
                'id' => (string) $user->id,
                'user_id' => (string) $user->id,
                'name' => $user->name ?? 'User',
                'username' => $user->username ?? $user->email,
                'email' => $user->email,
                'type' => $userType,
                'role' => $userType,
                'image' => $user->avatar ?? 'default-avatar.png',
                'phone' => $user->phone ?? '',
                'address' => $user->address ?? '',
                'sch_name' => 'School',
                'currency_symbol' => config('app.currency_symbol', 'KSh'),
                'currency_name' => config('app.currency_code', 'KES'),
                'date_format' => 'd-m-Y',
                'start_week' => 'Monday',
                'superadmin_restriction' => '0',
                'language' => [
                    'short_code' => 'en',
                    'name' => 'English'
                ],
            ];

            // Add student-specific fields
            if ($isStudent) {
                $userData['student_id'] = (string) $user->id;
                $userData['admission_no'] = $user->admission_no ?? 'N/A';
                $userData['class'] = 'Class A';
                $userData['section'] = 'Section A';
                $userData['student_session_id'] = '1';
            }

            // Add parent-specific fields
            if ($isParent) {
                $userData['parent_childs'] = [];
            }

            // Return in the format Android app expects
            // Using status "1" for success as per Android app checks
            $response = [
                'status' => '1',
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'accessToken' => $token,
                'access_token' => $token,
                'record' => $userData,
                'user' => $userData,
                'data' => $userData,
                'user_id' => (string) $user->id,
            ];

            \Log::info('Login Response:', ['response' => $response]);

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error("Login error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => '0',
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function me(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'school_id' => $user->school_id,
                ],
            ],
            'message' => 'User profile retrieved successfully',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return $this->successResponse(null, 'Logged out successfully');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Generate password reset token
        $token = \Illuminate\Support\Str::random(60);
        
        // Store the token in password_resets table
        \Illuminate\Support\Facades\DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // Send email with reset link
        // TODO: Implement email sending
        // For now, return success message
        return response()->json([
            'message' => 'Password reset link sent to your email',
            'token' => $token // Remove this in production
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $resetRecord = \Illuminate\Support\Facades\DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$resetRecord) {
            return response()->json(['message' => 'Invalid or expired reset token'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => bcrypt($request->password)]);

        // Delete the reset record
        \Illuminate\Support\Facades\DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
}


