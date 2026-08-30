<?php

namespace App\Repositories;

use App\Models\Control;
use App\Repositories\Contracts\ControlRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ControlRepository extends BaseRepository implements ControlRepositoryInterface
{
    public function model(): string
    {
        return Control::class;
    }

    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (isset($filters['domain_id'])) {
            $query->where('domain_id', $filters['domain_id']);
        }

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('control_id', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('control_id')->get();
    }

    public function getPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['domain_id'])) {
            $query->where('domain_id', $filters['domain_id']);
        }

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('control_id', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('control_id')->paginate($perPage);
    }

    public function getByDomain(int $domainId): Collection
    {
        return $this->model
            ->where('domain_id', $domainId)
            ->where('is_active', true)
            ->orderBy('control_id')
            ->get();
    }

    public function getByControlId(string $controlId): ?Control
    {
        return $this->model
            ->where('control_id', $controlId)
            ->first();
    }
}