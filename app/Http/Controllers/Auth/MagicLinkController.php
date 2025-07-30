<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MagicLinkController extends Controller
{
    /**
     * Show the magic link request form.
     */
    public function show()
    {
        return view('auth.magic-link');
    }

    /**
     * Send magic link to user's email.
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Your account is not activated. Please check your email for the verification link.']);
        }

        // Generate a unique token
        $token = Str::random(64);
        
        // Store the token in the database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send magic link email
        $magicLink = URL::temporarySignedRoute(
            'magic.link.verify',
            Carbon::now()->addMinutes(15),
            ['email' => $request->email, 'token' => $token]
        );

        Mail::send('emails.magic-link', [
            'user' => $user,
            'magicLink' => $magicLink
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Login to DUNCO SMS - Magic Link');
        });

        return back()->with('status', 'Magic link sent! Please check your email.');
    }

    /**
     * Verify magic link and login user.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid magic link.']);
        }

        if (!$user->is_active) {
            return redirect()->route('login')->withErrors(['email' => 'Your account is not activated.']);
        }

        // Verify the token
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord || !Hash::check($request->token, $tokenRecord->token)) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid or expired magic link.']);
        }

        // Check if token is expired (15 minutes)
        if (Carbon::parse($tokenRecord->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('login')->withErrors(['email' => 'Magic link has expired. Please request a new one.']);
        }

        // Login the user
        auth()->login($user);

        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('dashboard')->with('status', 'Welcome back!');
    }
} 