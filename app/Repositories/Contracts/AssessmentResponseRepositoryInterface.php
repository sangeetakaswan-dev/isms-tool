<?php

namespace App\Repositories\Contracts;

use App\Models\AssessmentResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AssessmentResponseRepositoryInterface
{
    public function getAllForAssessment(int $assessmentId, array $filters = []): Collection;

    public function getPaginatedForAssessment(int $assessmentId, int $perPage = 25, array $filters = []): LengthAwarePaginator;

    public function getByControlAndAssessment(int $assessmentId, int $controlId): ?AssessmentResponse;

    public function getByDomain(int $assessmentId, int $domainId): Collection;

    public function bulkUpdate(int $assessmentId, array $controlIds, string $status): int;

    public function bulkAssign(int $assessmentId, array $controlIds, int $userId): int;

    public function getProgressStats(int $assessmentId): array;

    public function getResponsesByStatus(int $assessmentId, string $status): Collection;

    public function getUnassessedControls(int $assessmentId): Collection;

    public function initializeResponses(int $assessmentId): int;
}