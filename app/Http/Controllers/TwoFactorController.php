<?php

namespace App\Http\Controllers;

use App\Models\UserTwoFactor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class TwoFactorController extends Controller
{
    /**
     * Display two-factor authentication setup page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $twoFactor = $user->twoFactor;

        $data = [
            "twoFactor" => $twoFactor,
            "qrCode" =>
                $twoFactor && $twoFactor->is_enabled
                    ? null
                    : $this->generateQrCode($user),
            "backupCodes" =>
                $twoFactor && $twoFactor->backup_codes
                    ? $twoFactor->backup_codes
                    : [],
        ];

        // Default to Blade view for better compatibility
        return view("security.two-factor", $data);

        // Uncomment below if you prefer Inertia.js rendering
        // return inertia("Auth/TwoFactor/Index", $data);
    }

    /**
     * Enable two-factor authentication
     */
    public function enable(Request $request): JsonResponse
    {
        try {
            $request->validate([
                "method" => ["required", Rule::in(["sms", "email", "app"])],
                "phone" => ["required_if:method,sms", "nullable", "string"],
                "email" => ["required_if:method,email", "nullable", "email"],
            ]);

            $user = Auth::user();

            // Generate secret key for all methods (even email/SMS can use it as verification token)
            $google2fa = new Google2FA();
            $secretKey = $google2fa->generateSecretKey();

            $twoFactor = UserTwoFactor::updateOrCreate(
                ["user_id" => $user->id],
                [
                    "method" => $request->input("method"),
                    "phone" => $request->input("phone"),
                    "email" => $request->input("email", $user->email), // Use user's email as default
                    "secret_key" => $secretKey,
                    "is_enabled" => false,
                    "verified_at" => null,
                ],
            );

            // Generate backup codes
            $backupCodes = [];
            for ($i = 0; $i < 10; $i++) {
                $backupCodes[] = Str::random(8);
            }

            $twoFactor->backup_codes = $backupCodes;
            $twoFactor->save();

            $method = $request->input("method");
            $response = [
                "success" => true,
                "backup_codes" => $backupCodes,
                "secret_key" => $secretKey,
            ];

            if ($method === "app") {
                $response["qr_code"] = $this->generateQrCode($user);
            } elseif ($method === "email") {
                // For email method, generate a 6-digit verification code
                $verificationCode = sprintf("%06d", random_int(100000, 999999));

                // Store verification code temporarily (you can use cache or session)
                cache()->put(
                    "2fa_email_code_{$user->id}",
                    $verificationCode,
                    now()->addMinutes(10),
                );

                // Send email with verification code
                $this->sendEmailVerificationCode($user, $verificationCode);

                $response["message"] =
                    "Verification code sent to your email address.";
            } elseif ($method === "sms") {
                // For SMS method, generate a 6-digit verification code
                $verificationCode = sprintf("%06d", random_int(100000, 999999));

                // Store verification code temporarily
                cache()->put(
                    "2fa_sms_code_{$user->id}",
                    $verificationCode,
                    now()->addMinutes(10),
                );

                // Send SMS with verification code (implement based on your SMS provider)
                $this->sendSmsVerificationCode(
                    $user,
                    $verificationCode,
                    $request->input("phone"),
                );

                $response["message"] =
                    "Verification code sent to your phone number.";
            }

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error("Two-factor enable failed: " . $e->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" =>
                        "Failed to setup two-factor authentication. Please try again.",
                ],
                500,
            );
        }
    }

    /**
     * Verify and enable two-factor authentication
     */
    public function verify(Request $request): JsonResponse
    {
        try {
            $request->validate([
                "code" => ["required", "string"],
            ]);

            $user = Auth::user();
            $twoFactor = $user->twoFactor;

            if (!$twoFactor) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Two-factor authentication is not set up.",
                    ],
                    400,
                );
            }

            $code = preg_replace("/\s+/", "", $request->input("code")); // Remove any spaces

            if (strlen($code) !== 6 || !ctype_digit($code)) {
                return response()->json(
                    [
                        "success" => false,
                        "message" =>
                            "Invalid verification code format. Please enter a 6-digit code.",
                    ],
                    400,
                );
            }

            $valid = false;

            // Verify based on method
            if ($twoFactor->method === "app") {
                if (!$twoFactor->secret_key) {
                    return response()->json(
                        [
                            "success" => false,
                            "message" =>
                                "Authenticator app is not properly set up.",
                        ],
                        400,
                    );
                }

                $google2fa = new Google2FA();
                $valid = $google2fa->verifyKey(
                    $twoFactor->secret_key,
                    $code,
                    2, // Allow 2 time windows (60 seconds each) for clock skew
                );
            } elseif ($twoFactor->method === "email") {
                // Verify email code from cache
                $cachedCode = cache()->get("2fa_email_code_{$user->id}");
                $valid = $cachedCode && $cachedCode === $code;

                if ($valid) {
                    // Clear the used code
                    cache()->forget("2fa_email_code_{$user->id}");
                }
            } elseif ($twoFactor->method === "sms") {
                // Verify SMS code from cache
                $cachedCode = cache()->get("2fa_sms_code_{$user->id}");
                $valid = $cachedCode && $cachedCode === $code;

                if ($valid) {
                    // Clear the used code
                    cache()->forget("2fa_sms_code_{$user->id}");
                }
            }

            if ($valid) {
                $twoFactor->is_enabled = true;
                $twoFactor->verified_at = now();
                $twoFactor->save();

                // Log the action
                if (class_exists("\App\Models\AuditLog")) {
                    \App\Models\AuditLog::log(
                        "2FA_ENABLED",
                        "Two-factor authentication enabled for user via {$twoFactor->method}",
                    );
                }

                return response()->json([
                    "success" => true,
                    "message" =>
                        "Two-factor authentication has been enabled successfully!",
                ]);
            }

            return response()->json(
                [
                    "success" => false,
                    "message" =>
                        "Invalid verification code. Please check your code and try again.",
                ],
                400,
            );
        } catch (\Exception $e) {
            \Log::error("Two-factor verification failed: " . $e->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" =>
                        "An error occurred during verification. Please try again.",
                ],
                500,
            );
        }
    }

    /**
     * Disable two-factor authentication
     */
    public function disable(Request $request): JsonResponse
    {
        $request->validate([
            "password" => ["required", "current_password"],
            "code" => [
                "required_if:twoFactor.is_enabled,true",
                "string",
                "size:6",
            ],
        ]);

        $user = Auth::user();
        $twoFactor = $user->twoFactor;

        if (!$twoFactor) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Two-factor authentication is not enabled.",
                ],
                400,
            );
        }

        // Verify the code if 2FA is enabled
        if ($twoFactor->is_enabled && $twoFactor->secret_key) {
            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey(
                $twoFactor->secret_key,
                $request->input("code"),
            );

            if (!$valid) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Invalid verification code.",
                    ],
                    400,
                );
            }
        }

        $twoFactor->delete();

        // Log the action
        \App\Models\AuditLog::log(
            "2FA_DISABLED",
            "Two-factor authentication disabled for user",
        );

        return response()->json([
            "success" => true,
            "message" =>
                "Two-factor authentication has been disabled successfully!",
        ]);
    }

    /**
     * Generate new backup codes
     */
    public function regenerateBackupCodes(Request $request): JsonResponse
    {
        $user = Auth::user();
        $twoFactor = $user->twoFactor;

        if (!$twoFactor) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Two-factor authentication is not set up.",
                ],
                400,
            );
        }

        $backupCodes = [];
        for ($i = 0; $i < 10; $i++) {
            $backupCodes[] = Str::random(8);
        }

        $twoFactor->backup_codes = $backupCodes;
        $twoFactor->save();

        return response()->json([
            "success" => true,
            "backup_codes" => $backupCodes,
        ]);
    }

    /**
     * Generate QR code for authenticator app setup
     */
    private function generateQrCode($user)
    {
        $twoFactor = $user->twoFactor;

        if (!$twoFactor || !$twoFactor->secret_key) {
            return null;
        }

        try {
            $companyName = config("app.name", "School Management");
            $google2fa = new Google2FA();

            $qrCodeUrl = $google2fa->getQRCodeUrl(
                $user->email, // Use email instead of name for better compatibility
                $companyName,
                $twoFactor->secret_key,
            );

            // Generate QR code as base64 image
            $qrCode = QrCode::size(200)->generate($qrCodeUrl);

            return "data:image/svg+xml;base64," . base64_encode($qrCode);
        } catch (\Exception $e) {
            \Log::error("QR Code generation failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Send email verification code for 2FA setup
     */
    private function sendEmailVerificationCode($user, $code)
    {
        try {
            // Simple email sending - you can enhance this with proper email templates
            $subject =
                config("app.name") . " - Two-Factor Authentication Setup";
            $message = "Your verification code for enabling two-factor authentication is: $code\n\n";
            $message .= "This code will expire in 10 minutes.\n\n";
            $message .= "If you didn't request this, please ignore this email.";

            mail($user->email, $subject, $message, [
                "From" => config("mail.from.address"),
                "Reply-To" => config("mail.from.address"),
            ]);

            \Log::info("2FA email verification code sent", [
                "user_id" => $user->id,
            ]);
        } catch (\Exception $e) {
            \Log::error(
                "Failed to send 2FA email verification code: " .
                    $e->getMessage(),
            );
            // Don't throw exception here to avoid breaking the flow
        }
    }

    /**
     * Send SMS verification code for 2FA setup
     */
    private function sendSmsVerificationCode($user, $code, $phone)
    {
        try {
            // Placeholder for SMS sending - implement based on your SMS provider
            // For now, just log it for development purposes
            \Log::info("2FA SMS verification code (DEVELOPMENT ONLY)", [
                "user_id" => $user->id,
                "phone" => $phone,
                "code" => $code,
            ]);

            // TODO: Implement actual SMS sending using your preferred provider:
            // - Twilio
            // - Nexmo/Vonage
            // - AWS SNS
            // - Africa's Talking
            // etc.
        } catch (\Exception $e) {
            \Log::error(
                "Failed to send 2FA SMS verification code: " . $e->getMessage(),
            );
            // Don't throw exception here to avoid breaking the flow
        }
    }
    /**
     * Get two-factor authentication status
     */
    public function status(): JsonResponse
    {
        try {
            $user = Auth::user();
            $twoFactor = $user->twoFactor;

            if (!$twoFactor || !$twoFactor->is_enabled) {
                return response()->json([
                    "enabled" => false,
                    "method" => null,
                    "enabled_date" => null,
                    "backup_codes_count" => 0,
                ]);
            }

            return response()->json([
                "enabled" => true,
                "method" => $twoFactor->method,
                "method_display" => ucfirst(
                    $twoFactor->method === "app"
                        ? "Authenticator App"
                        : $twoFactor->method,
                ),
                "enabled_date" => $twoFactor->verified_at
                    ? $twoFactor->verified_at->format("M j, Y")
                    : null,
                "backup_codes_count" => $twoFactor->backup_codes
                    ? count($twoFactor->backup_codes)
                    : 0,
            ]);
        } catch (\Exception $e) {
            \Log::error("Two-factor status check failed: " . $e->getMessage());
            return response()->json(
                [
                    "enabled" => false,
                    "method" => null,
                    "enabled_date" => null,
                    "backup_codes_count" => 0,
                    "error" => "Failed to retrieve status",
                ],
                500,
            );
        }
    }
}
