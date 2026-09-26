<?php

namespace App\Repositories\Contracts;

use App\Models\Document;

interface DocumentRepositoryInterface
{
    public function create(array $data): Document;
    public function update(int $id, array $data): Document;
    public function find(int $id): ?Document;
    public function delete(int $id): void;
    public function getByTenant(int $tenantId);
    public function getDueForReview();
}
