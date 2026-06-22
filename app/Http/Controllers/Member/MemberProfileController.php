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
}