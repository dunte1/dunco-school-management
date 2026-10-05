<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Display the password change form
     */
    public function index()
    {
        return view("security.password");
    }

    /**
     * Update the user's password
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate(
            [
                "current_password" => ["required", "current_password"],
                "password" => [
                    "required",
                    "confirmed",
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised(),
                ],
            ],
            [
                "password.min" =>
                    "Password must be at least 8 characters long.",
                "password.letters" =>
                    "Password must contain at least one letter.",
                "password.mixed_case" =>
                    "Password must contain both uppercase and lowercase letters.",
                "password.numbers" =>
                    "Password must contain at least one number.",
                "password.symbols" =>
                    "Password must contain at least one special character.",
                "password.uncompromised" =>
                    "This password has been found in a data breach and should not be used.",
            ],
        );

        $user = Auth::user();

        // Update the password
        $user
            ->forceFill([
                "password" => Hash::make($request->password),
                "force_password_reset" => false,
            ])
            ->save();

        // Log the password change
        \App\Models\AuditLog::log(
            "PASSWORD_CHANGED",
            "User changed their password",
        );

        // Send confirmation email if user has email
        if ($user->email) {
            // You can add a notification here if needed
            // $user->notify(new \App\Notifications\PasswordChanged());
        }

        return response()->json([
            "success" => true,
            "message" => "Password updated successfully!",
        ]);
    }

    /**
     * Force password reset for admin purposes
     */
    public function forceReset(Request $request): JsonResponse
    {
        $request->validate([
            "user_id" => ["required", "exists:users,id"],
            "new_password" => [
                "required",
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        // Update the password and force reset flag
        $user
            ->forceFill([
                "password" => Hash::make($request->new_password),
                "force_password_reset" => true,
            ])
            ->save();

        // Log the admin action
        \App\Models\AuditLog::log(
            "PASSWORD_FORCE_RESET",
            "Admin force reset password for user: " . $user->name,
            null,
            ["user_id" => $user->id],
        );

        return response()->json([
            "success" => true,
            "message" => "Password has been reset successfully!",
        ]);
    }

    /**
     * Check if user needs to reset password
     */
    public function checkResetStatus(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            "needs_reset" => $user->force_password_reset ?? false,
        ]);
    }
}
