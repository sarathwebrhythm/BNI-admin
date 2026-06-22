<?php

namespace App\Repositories\Contracts;

interface MemberRepositoryInterface
{
    public function getAllPaginated($perPage = 15, $search = null, $filters = []);

    public function getAllForExport($search = null, $filters = []);

    public function getById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getTotalCount();

    public function checkEmailExists($email);
}