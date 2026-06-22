<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class MemberForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found.'
            ], 404);
        }

        if ($member->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your membership is inactive. Please contact the administrator.'
            ], 403);
        }

        $otp = rand(100000, 999999);

        $member->otp = $otp;
        $member->otp_expires_at = now()->addMinute();
        $member->otp_verified = false;
        $member->save();

        Mail::raw(
            "Your BNI password reset OTP is: {$otp}. This OTP will expire in 1 minute.",
            function ($message) use ($member) {
                $message->to($member->email)
                        ->subject('BNI Password Reset OTP');
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found.'
            ], 404);
        }

        if ($member->otp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.'
            ], 400);
        }

        if ($member->otp_expires_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired.'
            ], 400);
        }

        $member->otp_verified = true;
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found.'
            ], 404);
        }

        if (!$member->otp_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify OTP first.'
            ], 403);
        }

        $member->password = Hash::make($request->password);

        $member->otp = null;
        $member->otp_expires_at = null;
        $member->otp_verified = false;

        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.'
        ]);
    }
}