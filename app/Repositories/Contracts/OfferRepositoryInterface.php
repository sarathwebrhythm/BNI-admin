<?php

namespace App\Repositories\Contracts;

interface OfferRepositoryInterface
{
    public function getAllPaginated($perPage = 15, $search = null, $filters = []);

    public function getById($id);
    
    public function updateStatus($id, string $status);

    public function delete($id);
    
}