<?php

namespace App\Repositories\Eloquent;

use App\Models\Member;
use App\Repositories\Contracts\MemberRepositoryInterface;

class MemberRepository implements MemberRepositoryInterface
{
    protected $model;

    public function __construct(Member $model)
    {
        $this->model = $model;
    }

    public function getAllPaginated($perPage = 15, $search = null, $filters = [])
    {
        return $this->buildQuery($search, $filters)->paginate($perPage)->withQueryString();
    }

    public function getAllForExport($search = null, $filters = [])
    {
        return $this->buildQuery($search, $filters)->get();
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
        $member = $this->getById($id);
        $member->update($data);
        return $member;
    }

    public function delete($id)
    {
        $member = $this->getById($id);
        return $member->delete();
    }

    public function getTotalCount()
    {
        return $this->model->count();
    }

    public function checkEmailExists($email)
    {
        return $this->model->where('email', $email)->exists();
    }

    protected function buildQuery($search = null, $filters = [])
    {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('chapter', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['chapter'])) {
            $query->where('chapter', $filters['chapter']);
        }

        return $query->latest();
    }
}