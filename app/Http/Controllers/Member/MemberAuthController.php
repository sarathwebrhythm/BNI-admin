<?php

namespace App\Http\Controllers\Member;

use App\Models\Member;
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
                'id'            => $member->id,
                'bni_id'        => $member->bni_id,
                'name'          => $member->name,
                'email'         => $member->email,
                'phone'         => $member->phone,
                'company'       => $member->company,
                'chapter'       => $member->chapter,
                'designation'   => $member->designation,
                'profile_photo' => $member->profile_photo,
                'cover_photo'   => $member->cover_photo,
                'business_logo' => $member->business_logo,
                'joining_date'  => $member->joining_date,
                'expire_date'   => $member->expire_date,
                'offer_limit'   => $member->package ? $member->package->offer_limit : 1,
            ]
        ]);
    }

    public function profile()
    {
        $member = auth('member')->user();

        return response()->json([
            'success' => true,
            'member' => [
                'id'            => $member->id,
                'bni_id'        => $member->bni_id,
                'name'          => $member->name,
                'email'         => $member->email,
                'phone'         => $member->phone,
                'company'       => $member->company,
                'chapter'       => $member->chapter,
                'designation'   => $member->designation,
                'status'        => $member->status,
                'profile_photo' => $member->profile_photo,
                'cover_photo'   => $member->cover_photo,
                'business_logo' => $member->business_logo,
                'joining_date'  => $member->joining_date,
                'expire_date'   => $member->expire_date,
                'offer_limit'   => $member->package ? $member->package->offer_limit : 1,
            ]
        ]);
    }


    public function ssoLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        $member = Member::where('email', $email)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found.'
            ], 404);
        }

        if ($member->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active.'
            ], 403);
        }

        $token = Auth::guard('member')->login($member);

        return response()->json([
            'success' => true,
            'message' => 'SSO Login successful',
            'token' => $token,
            'member' => [
                'id'            => $member->id,
                'bni_id'        => $member->bni_id,
                'name'          => $member->name,
                'email'         => $member->email,
                'phone'         => $member->phone,
                'company'       => $member->company,
                'chapter'       => $member->chapter,
                'designation'   => $member->designation,
                'profile_photo' => $member->profile_photo,
                'cover_photo'   => $member->cover_photo,
                'business_logo' => $member->business_logo,
                'joining_date'  => $member->joining_date,
                'expire_date'   => $member->expire_date,
                'offer_limit'   => $member->package ? $member->package->offer_limit : 1,
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


    public function memberStats()
    {
        $member = auth('member')->user();

        $activeOffers  = \App\Models\Offer::where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->count();

        $redeemedCount = \App\Models\OfferStat::where('member_id', $member->id)
            ->where('type', 'redemption')
            ->count();

        $totalPartners = \App\Models\Member::where('status', 'active')->count();

        return response()->json([
            'success'        => true,
            'active_offers'  => $activeOffers,
            'redeemed_count' => $redeemedCount,
            'total_partners' => $totalPartners,
        ]);
    }
}
