<?php

namespace App\Repositories;

use App\Models\SoAVersion;
use App\Repositories\Contracts\SoAVersionRepositoryInterface;

class SoAVersionRepository implements SoAVersionRepositoryInterface
{
    public function create(array $data): SoAVersion
    {
        return SoAVersion::create($data);
    }

    public function find(int $id): ?SoAVersion
    {
        return SoAVersion::find($id);
    }

    public function getByAssessment(int $assessmentId)
    {
        return SoAVersion::where('assessment_id', $assessmentId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function latest(int $assessmentId): ?SoAVersion
    {
        return SoAVersion::where('assessment_id', $assessmentId)
            ->latest()
            ->first();
    }
}
