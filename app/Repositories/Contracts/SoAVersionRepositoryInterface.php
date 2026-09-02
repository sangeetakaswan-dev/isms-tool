<?php

namespace App\Repositories\Contracts;

use App\Models\SoAVersion;

interface SoAVersionRepositoryInterface
{
    public function create(array $data): SoAVersion;
    public function find(int $id): ?SoAVersion;
    public function getByAssessment(int $assessmentId);
    public function latest(int $assessmentId): ?SoAVersion;
}
