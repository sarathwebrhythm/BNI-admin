<?php

namespace App\Services;

use App\Repositories\Contracts\MemberRepositoryInterface;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Package;
use Carbon\Carbon;
use App\Models\Offer;

class MemberService
{
    protected $memberRepo;

    public function __construct(MemberRepositoryInterface $memberRepo)
    {
        $this->memberRepo = $memberRepo;
    }

    public function listMembers($perPage = 15, $search = null, $filters = [])
    {
        return $this->memberRepo->getAllPaginated($perPage, $search, $filters);
    }

    public function getMemberById($id)
    {
        return $this->memberRepo->getById($id);
    }

    public function createMember(array $data)
    {
        $data['password'] = Hash::make('BNI@' . $data['phone']);
        // Automatically set expiry date
        if (!empty($data['joining_date'])) {
            $data['expire_date'] = Carbon::parse($data['joining_date'])
                ->addYear()
                ->toDateString();
        }

        $basicPlan = Package::where('name', 'Basic')->first();

        if ($basicPlan) {
            $data['package_id'] = $basicPlan->id;
        }
        $member = $this->memberRepo->create($data);

        $this->logActivity("Created member: {$member->name} ({$member->email})");

        return $member;
    }

    public function updateMember($id, array $data)
    {
        $member = $this->memberRepo->update($id, $data);

        // If member status changes, update all their offers
        if (isset($data['status']) && $data['status'] === 'inactive') {
            Offer::where('member_id', $member->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        $this->logActivity("Updated member: {$member->name} ({$member->email})");

        return $member;
    }
    public function deleteMember($id)
    {
        $member = $this->memberRepo->getById($id);
        $name = $member->name;
        $email = $member->email;
        $this->memberRepo->delete($id);
        $this->logActivity("Deleted member: {$name} ({$email})");
    }

    public function getTotalMembersCount()
    {
        return $this->memberRepo->getTotalCount();
    }

    protected function logActivity($activity)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            ActivityLog::create([
                'admin_id' => $admin->id,
                'activity' => $activity,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
