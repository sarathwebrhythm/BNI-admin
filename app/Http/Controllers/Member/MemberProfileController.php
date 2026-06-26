<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Member;

class MemberProfileController extends Controller
{
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /** @var \App\Models\Member $member */
        $member = auth('member')->user();

        if ($member->profile_photo) {
            $oldPath = ltrim(str_replace('/storage/', '', $member->profile_photo), '/');
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $path = $request->file('photo')->store('member-photos', 'public');
        $url = '/storage/' . $path;

        $member->profile_photo = $url;
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated successfully.',
            'photo_url' => $url,
        ]);
    }
    public function updateCoverPhoto(Request $request)
    {
        $request->validate([
            'cover' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        /** @var \App\Models\Member $member */
        $member = auth('member')->user();

        if ($member->cover_photo) {
            $oldPath = ltrim(str_replace('/storage/', '', $member->cover_photo), '/');
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $path = $request->file('cover')->store('member-covers', 'public');
        $url = '/storage/' . $path;

        $member->cover_photo = $url;
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Cover photo updated successfully.',
            'cover_url' => $url,
        ]);
    }

    public function updateBusinessLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /** @var \App\Models\Member $member */
        $member = auth('member')->user();

        if ($member->business_logo) {
            $oldPath = ltrim(str_replace('/storage/', '', $member->business_logo), '/');
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $path = $request->file('logo')->store('member-logos', 'public');
        $url = '/storage/' . $path;

        $member->business_logo = $url;
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Business logo updated successfully.',
            'logo_url' => $url,
        ]);
    }
}
