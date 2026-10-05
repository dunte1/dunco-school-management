<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to provider.
     */
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle provider callback.
     * Restricts login to users already registered in the system (no auto‑provisioning).
     */
    public function callback(string $provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Throwable $e) {
            Log::warning('Social login failed: '.$e->getMessage(), ['provider' => $provider]);
            return redirect()->route('login')->with('error', 'Could not sign in with '.$provider.'. Please try again.');
        }

        if (!$socialUser || empty($socialUser->getEmail())) {
            return redirect()->route('login')->with('error', 'Your '.$provider.' account has no email address.');
        }

        // Only allow login for users that already exist and are active
        $user = User::where('email', $socialUser->getEmail())->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Your account is not registered. Contact your school admin.');
        }
        if (method_exists($user, 'is_active') ? !$user->is_active : (isset($user->is_active) && !$user->is_active)) {
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }

        Auth::login($user, true);
        request()->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
}


