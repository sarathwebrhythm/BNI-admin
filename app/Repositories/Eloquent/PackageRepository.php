<?php

namespace App\Repositories\Eloquent;

use App\Models\Package;
use App\Repositories\Contracts\PackageRepositoryInterface;

class PackageRepository implements PackageRepositoryInterface
{
    public function getAllPaginated($perPage = 15, $search = null)
    {
        return Package::latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return Package::findOrFail($id);
    }

    public function create(array $data)
    {
        return Package::create($data);
    }

    public function update($id, array $data)
    {
        $package = $this->getById($id);
        $package->update($data);

        return $package;
    }

    public function delete($id)
    {
        return Package::destroy($id);
    }
}