<?php

namespace App\Repositories\Contracts;

interface UploadHistoryRepositoryInterface
{
    public function getRecentUploads($limit = 5);

    public function getTotalUploadCount();

    public function create(array $data);
}