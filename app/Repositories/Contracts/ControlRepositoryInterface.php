<?php

namespace App\Repositories\Contracts;

use App\Models\Control;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ControlRepositoryInterface
{
    public function getAll(array $filters = []): Collection;

    public function getPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function getByDomain(int $domainId): Collection;

    public function getByControlId(string $controlId): ?Control;
}