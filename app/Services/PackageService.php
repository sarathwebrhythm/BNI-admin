<?php

namespace App\Services;

use App\Repositories\Contracts\PackageRepositoryInterface;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PackageService
{
    protected $packageRepo;

    public function __construct(PackageRepositoryInterface $packageRepo)
    {
        $this->packageRepo = $packageRepo;
    }

    public function listPackages($perPage = 15, $search = null)
    {
        return $this->packageRepo->getAllPaginated($perPage, $search);
    }

    public function getPackageById($id)
    {
        return $this->packageRepo->getById($id);
    }

    public function createPackage(array $data)
    {
        $package = $this->packageRepo->create($data);

        $this->logActivity("Created package: {$package->name}");

        return $package;
    }

    public function updatePackage($id, array $data)
    {
        $package = $this->packageRepo->update($id, $data);

        $this->logActivity("Updated package: {$package->name}");

        return $package;
    }

    public function deletePackage($id)
    {
        $package = $this->packageRepo->getById($id);

        $name = $package->name;

        $this->packageRepo->delete($id);

        $this->logActivity("Deleted package: {$name}");
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