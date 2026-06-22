<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('member')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $member = Auth::guard('member')->user();
        if ($member->status !== 'active') {
            Auth::guard('member')->logout();
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active. Please contact support.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'member' => [
                'id' => $member->id,
                'bni_id' => $member->bni_id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'company' => $member->company,
                'chapter' => $member->chapter,
                'designation' => $member->designation,
                'profile_photo' => $member->profile_photo,
            ]
        ]);
    }
    public function profile()
    {
        $member = auth('member')->user();

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'bni_id' => $member->bni_id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'company' => $member->company,
                'chapter' => $member->chapter,
                'designation' => $member->designation,
                'status' => $member->status,
            ]
        ]);
    }
    public function logout()
    {
        Auth::guard('member')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
