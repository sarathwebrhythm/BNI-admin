<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();
            ActivityLog::create([
                'admin_id' => $admin->id,
                'activity' => 'Admin logged in',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $admin->name);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetLink = route('admin.reset-password.show', ['token' => $token, 'email' => $email]);

        Mail::raw("To reset your BNI Admin password, visit this link: {$resetLink}", function ($message) use ($email) {
            $message->to($email)->subject('Reset BNI Admin Password');
        });

        return back()->with('success', 'We have emailed your password reset link! (Check storage/logs/laravel.log in local development)');
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = $request->password;

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($token, $record->token)) {
            return back()->withErrors(['email' => 'This password reset token is invalid or expired.']);
        }

        $admin = \App\Models\Admin::where('email', $email)->first();
        if ($admin) {
            $admin->password = Hash::make($password);
            $admin->save();

            DB::table('password_reset_tokens')->where('email', $email)->delete();

            ActivityLog::create([
                'admin_id' => $admin->id,
                'activity' => 'Password reset successfully',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('admin.login')->with('success', 'Your password has been reset! Please login with your new password.');
        }

        return back()->withErrors(['email' => 'Unable to find user with this email address.']);
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            ActivityLog::create([
                'admin_id' => $admin->id,
                'activity' => 'Admin logged out',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}