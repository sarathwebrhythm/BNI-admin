<?php

namespace App\Services;

use App\Repositories\Contracts\OfferRepositoryInterface;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class OfferService
{

    protected $offerRepo;
    protected $notificationService;

    public function __construct(OfferRepositoryInterface $offerRepo, NotificationService $notificationService)
    {
        $this->offerRepo = $offerRepo;
        $this->notificationService = $notificationService;
    }

    public function listOffers($perPage = 15, $search = null, $filters = [])
    {
        return $this->offerRepo->getAllPaginated($perPage, $search, $filters);
    }

    public function getOfferById($id)
    {
        return $this->offerRepo->getById($id);
    }

    // public function updateStatus($id, string $status)
    // {
    //     $offer = $this->offerRepo->updateStatus($id, $status);
    //     $this->logActivity("Updated offer status to {$status}: {$offer->discount} by {$offer->member->name}");
    //     return $offer;
    // }

    public function updateStatus($id, string $status)
    {
        $offer = $this->offerRepo->updateStatus($id, $status);

        // Create notification
        if ($status === 'active') {

            $this->notificationService->create(
                $offer->member_id,
                'Offer Approved',
                "Your offer '{$offer->discount}' has been approved.",
                'offer_approved',
                $offer->id,
                'offer'
            );
        } elseif ($status === 'rejected') {

            $this->notificationService->create(
                $offer->member_id,
                'Offer Rejected',
                "Your offer '{$offer->discount}' has been rejected.",
                'offer_rejected',
                $offer->id,
                'offer'
            );
        }

        $this->logActivity("Updated offer status to {$status}: {$offer->discount} by {$offer->member->name}");

        return $offer;
    }

    public function deleteOffer($id)
    {
        $offer = $this->offerRepo->getById($id);

        if ($offer->image) {
            $path = ltrim(str_replace('/storage/', '', $offer->image), '/');
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $name = $offer->discount;
        $this->offerRepo->delete($id);
        $this->logActivity("Deleted offer: {$name}");
    }

    protected function logActivity($activity)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin) {
            ActivityLog::create([
                'admin_id'   => $admin->id,
                'activity'   => $activity,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
