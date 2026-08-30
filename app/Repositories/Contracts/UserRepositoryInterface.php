<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAll(array $filters = []): Collection;
    public function getPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function findByEmail(string $email): ?User;
    public function getUsersByTenant(int $tenantId): Collection;
}