<?php

namespace App\Repositories\Eloquent;

use App\Models\OfferCategory;
use App\Repositories\Contracts\OfferCategoryRepositoryInterface;

class OfferCategoryRepository implements OfferCategoryRepositoryInterface
{
    protected $model;

    public function __construct(OfferCategory $model)
    {
        $this->model = $model;
    }

    public function getAllPaginated($perPage = 15, $search = null)
    {
        return $this->buildQuery($search)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->getById($id);

        $category->update($data);

        return $category;
    }

    public function delete($id)
    {
        $category = $this->getById($id);

        return $category->delete();
    }

    protected function buildQuery($search = null)
    {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->latest();
    }
}