<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MemberService;
use App\Repositories\Contracts\UploadHistoryRepositoryInterface;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    protected $memberService;
    protected $uploadHistoryRepo;

    public function __construct(
        MemberService $memberService,
        UploadHistoryRepositoryInterface $uploadHistoryRepo
    ) {
        $this->memberService = $memberService;
        $this->uploadHistoryRepo = $uploadHistoryRepo;
    }

    public function index()
    {
        $totalMembers = $this->memberService->getTotalMembersCount();
        $totalUploads = $this->uploadHistoryRepo->getTotalUploadCount();
        $recentUploads = $this->uploadHistoryRepo->getRecentUploads(5);
        $recentActivity = ActivityLog::with('admin')
            ->where('created_at', '>=', now()->subHours(12))
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalMembers',
            'totalUploads',
            'recentUploads',
            'recentActivity'
        ));
    }
}
