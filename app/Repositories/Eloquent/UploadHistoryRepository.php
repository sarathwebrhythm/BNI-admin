<?php

namespace App\Repositories\Eloquent;

use App\Models\UploadHistory;
use App\Repositories\Contracts\UploadHistoryRepositoryInterface;

class UploadHistoryRepository implements UploadHistoryRepositoryInterface
{
    protected $model;

    public function __construct(UploadHistory $model)
    {
        $this->model = $model;
    }

    public function getRecentUploads($limit = 5)
    {
        return $this->model->with('uploader')->latest()->limit($limit)->get();
    }

    public function getTotalUploadCount()
    {
        return $this->model->count();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}