<?php

namespace App\Services\Assessment;

use App\DTOs\AssessmentResponseData;
use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Domain;
use App\Repositories\Contracts\AssessmentResponseRepositoryInterface;
use App\Repositories\Contracts\AssessmentRepositoryInterface;
use App\Repositories\Contracts\ControlRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ResponseService
{
    public function __construct(
        private readonly AssessmentResponseRepositoryInterface $responseRepository,
        private readonly AssessmentRepositoryInterface $assessmentRepository,
        private readonly ControlRepositoryInterface $controlRepository,
    ) {
    }

    /**
     * Initialize all responses for a new assessment.
     */
    public function initializeResponses(Assessment $assessment): int
    {
        try {
            DB::beginTransaction();

            $count = $this->responseRepository->initializeResponses($assessment->id);

            $this->updateAssessmentProgress($assessment);

            DB::commit();

            Log::info('Assessment responses initialized', [
                'assessment_id' => $assessment->id,
                'response_count' => $count,
                'user_id' => auth()->id(),
            ]);

            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to initialize assessment responses', [
                'assessment_id' => $assessment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update a single response.
     */
    public function updateResponse(int $responseId, AssessmentResponseData $data): AssessmentResponse
    {
        try {
            DB::beginTransaction();

            $response = $this->responseRepository->find($responseId);

            if (!$response) {
                throw new \Exception('Assessment response not found');
            }

            $updateData = $data->toArray();

            if (isset($updateData['status']) && $updateData['status'] !== 'not_assessed') {
                $updateData['assessed_by'] = auth()->id();
                $updateData['assessed_at'] = now();
            }

            if (isset($updateData['status']) && $updateData['status'] === 'not_applicable') {
                if (empty($updateData['not_applicable_reason']) && empty($updateData['gap_description'])) {
                    throw new \Exception('Not applicable status requires justification');
                }
            }

            $updated = $this->responseRepository->update($responseId, $updateData);

            $assessment = $this->assessmentRepository->find($response->assessment_id);
            $this->updateAssessmentProgress($assessment);

            DB::commit();

            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update assessment response', [
                'response_id' => $responseId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Bulk update multiple responses.
     */
    public function bulkUpdate(int $assessmentId, array $controlIds, string $status): int
    {
        try {
            DB::beginTransaction();

            $validStatuses = ['compliant', 'non_compliant', 'partially_compliant', 'not_assessed'];

            if (!in_array($status, $validStatuses)) {
                throw new \Exception('Invalid status provided');
            }

            $count = $this->responseRepository->bulkUpdate($assessmentId, $controlIds, $status);

            $assessment = $this->assessmentRepository->find($assessmentId);
            $this->updateAssessmentProgress($assessment);

            DB::commit();

            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to bulk update responses', [
                'assessment_id' => $assessmentId,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Bulk assign controls to a user.
     */
    public function bulkAssign(int $assessmentId, array $controlIds, int $userId): int
    {
        try {
            DB::beginTransaction();

            $count = $this->responseRepository->bulkAssign($assessmentId, $controlIds, $userId);

            DB::commit();

            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to bulk assign controls', [
                'assessment_id' => $assessmentId,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Auto-assign controls using round-robin.
     */
    public function autoAssignControls(Assessment $assessment, array $assigneeIds): array
    {
        try {
            DB::beginTransaction();

            $unassignedResponses = $this->responseRepository->getUnassessedControls($assessment->id);
            $assigneeCount = count($assigneeIds);

            if ($assigneeCount === 0) {
                throw new \Exception('No assignees provided');
            }

            $assignmentCount = 0;
            $index = 0;

            foreach ($unassignedResponses as $response) {
                $userId = $assigneeIds[$index % $assigneeCount];
                $this->responseRepository->bulkAssign($assessment->id, [$response->control_id], $userId);
                $assignmentCount++;
                $index++;
            }

            DB::commit();

            return [
                'assigned_count' => $assignmentCount,
                'assignee_count' => $assigneeCount,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to auto-assign controls', [
                'assessment_id' => $assessment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Calculate maturity score for assessment.
     */
    public function calculateMaturityScore(Assessment $assessment): array
    {
        $responses = $this->responseRepository->getAllForAssessment($assessment->id);

        $assessedResponses = $responses->filter(function ($response) {
            return $response->maturity_level !== null &&
                $response->status !== 'not_applicable' &&
                $response->status !== 'not_assessed';
        });

        if ($assessedResponses->isEmpty()) {
            return [
                'average_maturity' => 0,
                'total_assessed' => 0,
                'maturity_distribution' => [],
            ];
        }

        $average = $assessedResponses->avg('maturity_level');

        $distribution = $assessedResponses->groupBy('maturity_level')
            ->map(fn($group) => $group->count())
            ->toArray();

        return [
            'average_maturity' => round($average, 2),
            'total_assessed' => $assessedResponses->count(),
            'maturity_distribution' => $distribution,
        ];
    }

    /**
     * Get all domain compliance summaries.
     */
    public function getAllDomainCompliance(Assessment $assessment): \Illuminate\Support\Collection
    {
        $domains = Domain::orderBy('sort_order')->get();
        $results = collect();

        foreach ($domains as $domain) {
            $results->push($this->getDomainCompliance($domain, $assessment));
        }

        return $results;
    }

    /**
     * Get domain compliance summary.
     */
    public function getDomainCompliance(Domain $domain, Assessment $assessment): array
    {
        $responses = $this->responseRepository->getByDomain($assessment->id, $domain->id);

        $total = $responses->count();
        $stats = [
            'compliant' => 0,
            'non_compliant' => 0,
            'partially_compliant' => 0,
            'not_applicable' => 0,
            'not_assessed' => 0,
        ];

        foreach ($responses as $response) {
            $stats[$response->status] = ($stats[$response->status] ?? 0) + 1;
        }

        $applicableTotal = $total - $stats['not_applicable'];
        $compliantCount = $stats['compliant'];
        $compliancePercentage = $applicableTotal > 0
            ? round(($compliantCount / $applicableTotal) * 100, 2)
            : 0;

        return [
            'domain_id' => $domain->id,
            'domain_code' => $domain->code,
            'domain_name' => $domain->name,
            'total_controls' => $total,
            'status_breakdown' => $stats,
            'compliant' => $stats['compliant'],
            'non_compliant' => $stats['non_compliant'],
            'partially_compliant' => $stats['partially_compliant'],
            'not_applicable' => $stats['not_applicable'],
            'not_assessed' => $stats['not_assessed'],
            'applicable_controls' => $applicableTotal,
            'compliance_percentage' => $compliancePercentage,
        ];
    }

    /**
     * Get complete compliance report for assessment.
     */
    public function getComplianceReport(Assessment $assessment): array
    {
        $domains = Domain::orderBy('sort_order')->get();
        $domainReports = [];

        foreach ($domains as $domain) {
            $domainReports[] = $this->getDomainCompliance($domain, $assessment);
        }

        $progressStats = $this->responseRepository->getProgressStats($assessment->id);
        $maturityScore = $this->calculateMaturityScore($assessment);

        return [
            'assessment' => $assessment->toArray(),
            'progress' => $progressStats,
            'maturity' => $maturityScore,
            'domains' => $domainReports,
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Update assessment progress percentage.
     */
    protected function updateAssessmentProgress(Assessment $assessment): void
    {
        $stats = $this->responseRepository->getProgressStats($assessment->id);
        $this->assessmentRepository->update($assessment->id, [
            'progress_percentage' => $stats['progress_percentage'],
        ]);
    }

    /**
     * Upload evidence for a response.
     */
    public function uploadEvidence(AssessmentResponse $response, $file): array
    {
        $assessment = $this->assessmentRepository->find($response->assessment_id);
        $tenantId = $assessment->tenant_id;

        $path = "evidence/{$tenantId}/{$assessment->id}/{$response->control_id}/";
        $filename = time() . '_' . $file->getClientOriginalName();

        $storedPath = $file->storeAs($path, $filename, 'public');

        $this->responseRepository->update($response->id, [
            'evidence_files' => json_encode([
                'path' => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_at' => now()->toISOString(),
                'uploaded_by' => auth()->id(),
            ]),
        ]);

        return [
            'path' => $storedPath,
            'url' => Storage::url($storedPath),
            'filename' => $file->getClientOriginalName(),
        ];
    }

    /**
     * Get progress statistics for assessment.
     */
    public function getProgressStats(int $assessmentId): array
    {
        return $this->responseRepository->getProgressStats($assessmentId);
    }

    /**
     * Get responses for a specific domain.
     */
    public function getResponsesByDomain(int $assessmentId, int $domainId): Collection
    {
        return $this->responseRepository->getByDomain($assessmentId, $domainId);
    }

    /**
     * Get paginated responses.
     */
    public function getPaginatedResponses(int $assessmentId, int $perPage = 25, array $filters = []): LengthAwarePaginator
    {
        return $this->responseRepository->getPaginatedForAssessment($assessmentId, $perPage, $filters);
    }

    /**
     * Get recent responses.
     */
    public function getRecentResponses(int $assessmentId, int $limit = 10): Collection
    {
        return $this->responseRepository->getAllForAssessment($assessmentId)
            ->sortByDesc('updated_at')
            ->take($limit);
    }
}