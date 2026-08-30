<?php

namespace App\Repositories\Contracts;

use App\Models\Assessment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface AssessmentRepositoryInterface
{
    public function getAll(array $filters = []): Collection;

    public function getPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function find(int $id, array $columns = ['*']): ?Model;

    public function create(array $data): Assessment;

    public function update(int $id, array $data): Assessment;

    public function delete(int $id): bool;
}