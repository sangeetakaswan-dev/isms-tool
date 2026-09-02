<?php

namespace App\Repositories\Contracts;

use App\Models\SoAEntry;

interface SoAEntryRepositoryInterface
{
    public function create(array $data): SoAEntry;
    public function update(int $id, array $data): SoAEntry;
    public function find(int $id): ?SoAEntry;
    public function delete(int $id): void;
    public function getByAssessment(int $assessmentId);
    public function getByControl(int $assessmentId, int $controlId): ?SoAEntry;
}
