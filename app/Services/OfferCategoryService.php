<?php

namespace App\Services;

use App\Repositories\Contracts\OfferCategoryRepositoryInterface;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OfferCategoryService
{
    protected $offerCategoryRepo;

    public function __construct(OfferCategoryRepositoryInterface $offerCategoryRepo)
    {
        $this->offerCategoryRepo = $offerCategoryRepo;
    }

    public function listCategories($perPage = 15, $search = null, $status = null)
    {
        return $this->offerCategoryRepo->getAllPaginated($perPage, $search, $status);
    }

    public function getCategoryById($id)
    {
        return $this->offerCategoryRepo->getById($id);
    }

    public function createCategory(array $data, ?UploadedFile $icon = null)
    {
        if ($icon) {
            $data['icon'] = $icon->store('offer-categories', 'public');
        }

        $category = $this->offerCategoryRepo->create($data);

        $this->logActivity("Created offer category: {$category->name}");

        return $category;
    }
    public function updateCategory($id, array $data, ?UploadedFile $icon = null)
    {
        $category = $this->offerCategoryRepo->getById($id);

        if ($icon) {

            // Delete old icon
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            // Upload new icon
            $data['icon'] = $icon->store('offer-categories', 'public');
        }

        $category = $this->offerCategoryRepo->update($id, $data);

        $this->logActivity("Updated offer category: {$category->name}");

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->offerCategoryRepo->getById($id);

        if ($category->icon && Storage::disk('public')->exists($category->icon)) {
            Storage::disk('public')->delete($category->icon);
        }

        $name = $category->name;

        $this->offerCategoryRepo->delete($id);

        $this->logActivity("Deleted offer category: {$name}");
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
