<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $member = auth('member')->user();

        $notifications = Notification::where('member_id', $member->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    public function unreadCount()
    {
        $member = auth('member')->user();

        $count = Notification::where('member_id', $member->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    public function markAsRead($id)
    {
        $member = auth('member')->user();

        $notification = Notification::where('member_id', $member->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->update([
            'is_read' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }
}
