<?php

namespace App\Repositories\Contracts;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TenantRepositoryInterface
{
    public function getAll(array $filters = []): Collection;
    public function getPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function findBySlug(string $slug): ?Tenant;
}