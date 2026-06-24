<?php

namespace App\Repositories\Contracts;

interface OfferCategoryRepositoryInterface
{
    public function getAllPaginated($perPage = 15, $search = null);

    public function getById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}