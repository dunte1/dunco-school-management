<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserTwoFactor;
use App\Models\UserBiometric;
use App\Notifications\TwoFactorCode;

class AdvancedAuthController extends BaseMobileController
{
    /**
     * Setup Two-Factor Authentication
     */
    public function setupTwoFactor(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'method' => 'required|string|in:sms,email,app',
            'phone' => 'required_if:method,sms|string|max:20',
            'email' => 'required_if:method,email|email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        try {
            $user = $request->user();
            $method = $request->input('method');

            // Generate backup codes
            $backupCodes = $this->generateBackupCodes();

            // Create or update 2FA record
            $twoFactor = UserTwoFactor::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'method' => $method,
                'phone' => $request->input('phone'),
                'email' => $request->input('email', $user->email),
                'backup_codes' => json_encode($backupCodes),
                'is_enabled' => false, // Will be enabled after verification
                'secret_key' => $method === 'app' ? $this->generateSecretKey() : null,
            ]);

            // Send verification code
            $verificationCode = $this->sendVerificationCode($twoFactor);

            return $this->successResponse([
                'message' => '2FA setup initiated. Please verify with the code sent.',
                'method' => $method,
                'backup_codes' => $backupCodes,
                'setup_id' => $twoFactor->id,
                'qr_code' => $method === 'app' ? $this->generateQRCode($user, $twoFactor->secret_key) : null,
                'verification_required' => true,
            ]);

        } catch (\Exception $e) {
            Log::error('2FA setup error: ' . $e->getMessage());
            return $this->errorResponse('Failed to setup 2FA', 500);
        }
    }

    /**
     * Verify Two-Factor Authentication setup
     */
    public function verifyTwoFactorSetup(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'setup_id' => 'required|integer|exists:user_two_factors,id',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        try {
            $user = $request->user();
            $setupId = $request->input('setup_id');
            $code = $request->input('code');

            $twoFactor = UserTwoFactor::where('id', $setupId)
                ->where('user_id', $user->id)
                ->first();

            if (!$twoFactor) {
                return $this->errorResponse('Invalid setup ID', 404);
            }

            // Verify the code
            if (!$this->verifyCode($twoFactor, $code)) {
                return $this->errorResponse('Invalid verification code', 422);
            }

            // Enable 2FA
            $twoFactor->update([
                'is_enabled' => true,
                'verified_at' => now(),
            ]);

            return $this->successResponse([
                'message' => '2FA has been successfully enabled',
                'method' => $twoFactor->method,
                'backup_codes' => json_decode($twoFactor->backup_codes),
                'is_enabled' => true,
            ]);

        } catch (\Exception $e) {
            Log::error('2FA verification error: ' . $e->getMessage());
            return $this->errorResponse('Failed to verify 2FA', 500);
        }
    }

    /**
     * Login with Two-Factor Authentication
     */
    public function loginWithTwoFactor(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'two_factor_code' => 'nullable|string|size:6',
            'backup_code' => 'nullable|string',
            'remember_device' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $twoFactorCode = $request->input('two_factor_code');
            $backupCode = $request->input('backup_code');
            $rememberDevice = $request->input('remember_device', false);

            // Find user
            $user = User::where('email', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                return $this->errorResponse('Invalid credentials', 401);
            }

            // Check if 2FA is enabled
            $twoFactor = UserTwoFactor::where('user_id', $user->id)
                ->where('is_enabled', true)
                ->first();

            if (!$twoFactor) {
                // Regular login without 2FA
                return $this->performLogin($user, $request);
            }

            // Check if device is trusted (if remember_device was used before)
            $deviceId = $request->header('Device-ID') ?? $request->ip();
            if ($this->isDeviceTrusted($user->id, $deviceId)) {
                return $this->performLogin($user, $request);
            }

            // Require 2FA
            if (!$twoFactorCode && !$backupCode) {
                // Send 2FA code and require it
                $this->sendVerificationCode($twoFactor);
                
                return $this->errorResponse('2FA code required', 422, [
                    'requires_2fa' => true,
                    'method' => $twoFactor->method,
                    'partial_phone' => $twoFactor->method === 'sms' ? $this->maskPhone($twoFactor->phone) : null,
                    'partial_email' => $twoFactor->method === 'email' ? $this->maskEmail($twoFactor->email) : null,
                ]);
            }

            // Verify 2FA code or backup code
            $verified = false;
            if ($twoFactorCode) {
                $verified = $this->verifyCode($twoFactor, $twoFactorCode);
            } elseif ($backupCode) {
                $verified = $this->verifyBackupCode($twoFactor, $backupCode);
            }

            if (!$verified) {
                return $this->errorResponse('Invalid 2FA code', 401);
            }

            // Remember device if requested
            if ($rememberDevice) {
                $this->trustDevice($user->id, $deviceId);
            }

            return $this->performLogin($user, $request);

        } catch (\Exception $e) {
            Log::error('2FA login error: ' . $e->getMessage());
            return $this->errorResponse('Login failed', 500);
        }
    }

    /**
     * Setup Biometric Authentication
     */
    public function setupBiometric(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'biometric_type' => 'required|string|in:fingerprint,face,voice',
            'device_id' => 'required|string',
            'biometric_data' => 'required|string', // Encrypted biometric template
            'public_key' => 'required|string', // For encryption/decryption
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        try {
            $user = $request->user();

            // Create biometric record
            $biometric = UserBiometric::updateOrCreate([
                'user_id' => $user->id,
                'device_id' => $request->input('device_id'),
                'biometric_type' => $request->input('biometric_type'),
            ], [
                'biometric_data' => $request->input('biometric_data'),
                'public_key' => $request->input('public_key'),
                'is_enabled' => true,
                'created_at' => now(),
            ]);

            return $this->successResponse([
                'message' => 'Biometric authentication has been successfully setup',
                'biometric_id' => $biometric->id,
                'biometric_type' => $biometric->biometric_type,
                'device_id' => $biometric->device_id,
                'is_enabled' => true,
            ]);

        } catch (\Exception $e) {
            Log::error('Biometric setup error: ' . $e->getMessage());
            return $this->errorResponse('Failed to setup biometric authentication', 500);
        }
    }

    /**
     * Login with Biometric Authentication
     */
    public function loginWithBiometric(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'biometric_type' => 'required|string|in:fingerprint,face,voice',
            'biometric_signature' => 'required|string', // Signed challenge
            'challenge_response' => 'required|string', // Response to server challenge
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        try {
            $deviceId = $request->input('device_id');
            $biometricType = $request->input('biometric_type');
            $signature = $request->input('biometric_signature');
            $challengeResponse = $request->input('challenge_response');

            // Find biometric record
            $biometric = UserBiometric::where('device_id', $deviceId)
                ->where('biometric_type', $biometricType)
                ->where('is_enabled', true)
                ->with('user')
                ->first();

            if (!$biometric) {
                return $this->errorResponse('Biometric authentication not setup for this device', 404);
            }

            // Verify challenge response
            if (!$this->verifyBiometricChallenge($biometric, $challengeResponse, $signature)) {
                return $this->errorResponse('Biometric verification failed', 401);
            }

            // Update last used
            $biometric->update(['last_used' => now()]);

            // Perform login
            return $this->performLogin($biometric->user, $request);

        } catch (\Exception $e) {
            Log::error('Biometric login error: ' . $e->getMessage());
            return $this->errorResponse('Biometric login failed', 500);
        }
    }

    /**
     * Get user's security settings
     */
    public function getSecuritySettings(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $twoFactor = UserTwoFactor::where('user_id', $user->id)->first();
            $biometrics = UserBiometric::where('user_id', $user->id)
                ->where('is_enabled', true)
                ->get();

            $trustedDevices = Cache::get("trusted_devices_user_{$user->id}", []);

            return $this->successResponse([
                'two_factor_auth' => [
                    'is_enabled' => $twoFactor && $twoFactor->is_enabled,
                    'method' => $twoFactor->method ?? null,
                    'verified_at' => $twoFactor->verified_at ?? null,
                    'backup_codes_count' => $twoFactor ? count(json_decode($twoFactor->backup_codes ?? '[]')) : 0,
                ],
                'biometric_auth' => $biometrics->map(function ($biometric) {
                    return [
                        'id' => $biometric->id,
                        'type' => $biometric->biometric_type,
                        'device_id' => $biometric->device_id,
                        'setup_date' => $biometric->created_at->toISOString(),
                        'last_used' => $biometric->last_used?->toISOString(),
                    ];
                }),
                'trusted_devices' => count($trustedDevices),
                'security_score' => $this->calculateSecurityScore($user, $twoFactor, $biometrics),
                'recommendations' => $this->getSecurityRecommendations($user, $twoFactor, $biometrics),
            ]);

        } catch (\Exception $e) {
            Log::error('Get security settings error: ' . $e->getMessage());
            return $this->errorResponse('Failed to get security settings', 500);
        }
    }

    // Helper methods

    protected function generateBackupCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(Str::random(8));
        }
        return $codes;
    }

    protected function generateSecretKey(): string
    {
        return base32_encode(random_bytes(32));
    }

    protected function generateQRCode(User $user, string $secret): string
    {
        $appName = config('app.name');
        $otpUrl = "otpauth://totp/{$appName}:{$user->email}?secret={$secret}&issuer={$appName}";
        
        // Return base64 encoded QR code (you would use a QR code library here)
        return base64_encode("QR_CODE_FOR: " . $otpUrl);
    }

    protected function sendVerificationCode(UserTwoFactor $twoFactor): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store code in cache for 5 minutes
        Cache::put("2fa_code_{$twoFactor->user_id}", $code, 300);

        // Send via chosen method
        switch ($twoFactor->method) {
            case 'sms':
                // Implement SMS sending logic
                Log::info("2FA SMS code sent to {$twoFactor->phone}: {$code}");
                break;
            case 'email':
                $twoFactor->user->notify(new TwoFactorCode($code));
                break;
            case 'app':
                // For authenticator apps, no code needs to be sent
                break;
        }

        return $code;
    }

    protected function verifyCode(UserTwoFactor $twoFactor, string $code): bool
    {
        if ($twoFactor->method === 'app') {
            // Verify TOTP code (implement TOTP verification)
            return $this->verifyTOTP($twoFactor->secret_key, $code);
        }

        // Verify cached code
        $cachedCode = Cache::get("2fa_code_{$twoFactor->user_id}");
        return $cachedCode === $code;
    }

    protected function verifyBackupCode(UserTwoFactor $twoFactor, string $code): bool
    {
        $backupCodes = json_decode($twoFactor->backup_codes, true);
        
        if (in_array(strtoupper($code), $backupCodes)) {
            // Remove used backup code
            $backupCodes = array_diff($backupCodes, [strtoupper($code)]);
            $twoFactor->update(['backup_codes' => json_encode(array_values($backupCodes))]);
            return true;
        }

        return false;
    }

    protected function verifyTOTP(string $secret, string $code): bool
    {
        // Implement TOTP verification (Time-based One-Time Password)
        // This is a simplified version - use a proper TOTP library
        $time = floor(time() / 30);
        $expectedCode = substr(hash_hmac('sha1', pack('N*', 0) . pack('N*', $time), base32_decode($secret)), -6);
        return $expectedCode === $code;
    }

    protected function isDeviceTrusted(int $userId, string $deviceId): bool
    {
        $trustedDevices = Cache::get("trusted_devices_user_{$userId}", []);
        return isset($trustedDevices[$deviceId]) && $trustedDevices[$deviceId] > now()->timestamp;
    }

    protected function trustDevice(int $userId, string $deviceId): void
    {
        $trustedDevices = Cache::get("trusted_devices_user_{$userId}", []);
        $trustedDevices[$deviceId] = now()->addDays(30)->timestamp; // Trust for 30 days
        Cache::put("trusted_devices_user_{$userId}", $trustedDevices, 86400 * 31); // 31 days
    }

    protected function verifyBiometricChallenge(UserBiometric $biometric, string $challengeResponse, string $signature): bool
    {
        // Implement biometric verification logic
        // This would involve cryptographic verification of the biometric signature
        // For now, return true as a placeholder
        return true;
    }

    protected function performLogin(User $user, Request $request): JsonResponse
    {
        // Create token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return $this->successResponse([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->first()->name ?? 'user',
            ],
            'token' => $token,
            'expires_at' => now()->addDays(30)->toISOString(),
        ]);
    }

    protected function maskPhone(string $phone): string
    {
        return substr($phone, 0, 3) . '****' . substr($phone, -2);
    }

    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        return substr($parts[0], 0, 2) . '****@' . $parts[1];
    }

    protected function calculateSecurityScore(User $user, ?UserTwoFactor $twoFactor, $biometrics): int
    {
        $score = 30; // Base score

        if ($twoFactor && $twoFactor->is_enabled) {
            $score += 40;
        }

        if ($biometrics->count() > 0) {
            $score += 30;
        }

        return min($score, 100);
    }

    protected function getSecurityRecommendations(User $user, ?UserTwoFactor $twoFactor, $biometrics): array
    {
        $recommendations = [];

        if (!$twoFactor || !$twoFactor->is_enabled) {
            $recommendations[] = 'Enable Two-Factor Authentication for better security';
        }

        if ($biometrics->count() === 0) {
            $recommendations[] = 'Setup biometric authentication for quick and secure access';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'Your account security is excellent!';
        }

        return $recommendations;
    }
}

// Helper function for base32 encoding (if not available)
if (!function_exists('base32_encode')) {
    function base32_encode($data) {
        return base64_encode($data); // Simplified
    }
}

if (!function_exists('base32_decode')) {
    function base32_decode($data) {
        return base64_decode($data); // Simplified
    }
}