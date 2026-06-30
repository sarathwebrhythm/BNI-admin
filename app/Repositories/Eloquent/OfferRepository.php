<?php

namespace App\Repositories\Eloquent;

use App\Models\Offer;
use App\Repositories\Contracts\OfferRepositoryInterface;

class OfferRepository implements OfferRepositoryInterface
{
    protected $model;

    public function __construct(Offer $model)
    {
        $this->model = $model;
    }

    public function getAllPaginated($perPage = 15, $search = null, $filters = [])
    {
        return $this->buildQuery($search, $filters)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getById($id)
    {
        return $this->model->with(['member', 'category'])->findOrFail($id);
    }

    public function updateStatus($id, string $status)
    {
        $offer = $this->getById($id);
        $offer->update(['status' => $status]);
        return $offer;
    }

    public function delete($id)
    {
        $offer = $this->getById($id);
        return $offer->delete();
    }

    protected function buildQuery($search = null, $filters = [])
    {
        $query = $this->model->with(['member', 'category']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('discount', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('member', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category'])) {
            $query->where('offer_category_id', $filters['category']);
        }

        return $query->latest();
    }
}