<?php

namespace App\Repositories;

use App\Models\AssessmentResponse;
use App\Repositories\Contracts\AssessmentResponseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AssessmentResponseRepository extends BaseRepository implements AssessmentResponseRepositoryInterface
{
    public function model(): string
    {
        return AssessmentResponse::class;
    }

    public function getAllForAssessment(int $assessmentId, array $filters = []): Collection
    {
        $query = $this->model
            ->where('assessment_id', $assessmentId)
            ->with(['control.domain', 'assignedTo', 'assessedBy']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['domain_id'])) {
            $query->whereHas('control', function ($q) use ($filters) {
                $q->where('domain_id', $filters['domain_id']);
            });
        }

        return $query->get();
    }

    public function getPaginatedForAssessment(int $assessmentId, int $perPage = 25, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model
            ->where('assessment_id', $assessmentId)
            ->with(['control.domain', 'assignedTo', 'assessedBy']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['domain_id'])) {
            $query->whereHas('control', function ($q) use ($filters) {
                $q->where('domain_id', $filters['domain_id']);
            });
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('control', function ($q) use ($search) {
                $q->where('control_id', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function getByControlAndAssessment(int $assessmentId, int $controlId): ?AssessmentResponse
    {
        return $this->model
            ->where('assessment_id', $assessmentId)
            ->where('control_id', $controlId)
            ->with(['control.domain', 'assignedTo', 'assessedBy'])
            ->first();
    }

    public function getByDomain(int $assessmentId, int $domainId): Collection
    {
        return $this->model
            ->where('assessment_id', $assessmentId)
            ->whereHas('control', function ($q) use ($domainId) {
                $q->where('domain_id', $domainId);
            })
            ->with(['control', 'assignedTo', 'assessedBy'])
            ->get();
    }

    public function bulkUpdate(int $assessmentId, array $controlIds, string $status): int
    {
        return $this->model
            ->where('assessment_id', $assessmentId)
            ->whereIn('control_id', $controlIds)
            ->update([
                'status' => $status,
                'assessed_by' => auth()->id(),
                'assessed_at' => now(),
            ]);
    }

    public function bulkAssign(int $assessmentId, array $controlIds, int $userId): int
    {
        return $this->model
            ->where('assessment_id', $assessmentId)
            ->whereIn('control_id', $controlIds)
            ->update([
                'assigned_to' => $userId,
                'updated_at' => now(),
            ]);
    }

    public function getProgressStats(int $assessmentId): array
    {
        $total = $this->model->where('assessment_id', $assessmentId)->count();

        if ($total === 0) {
            return [
                'total' => 0,
                'assessed' => 0,
                'not_assessed' => 0,
                'compliant' => 0,
                'non_compliant' => 0,
                'partially_compliant' => 0,
                'not_applicable' => 0,
                'progress_percentage' => 0,
            ];
        }

        $stats = $this->model
            ->where('assessment_id', $assessmentId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $assessed = $total - ($stats['not_assessed'] ?? 0);
        $progress = ($assessed / $total) * 100;

        return [
            'total' => $total,
            'assessed' => $assessed,
            'not_assessed' => $stats['not_assessed'] ?? 0,
            'compliant' => $stats['compliant'] ?? 0,
            'non_compliant' => $stats['non_compliant'] ?? 0,
            'partially_compliant' => $stats['partially_compliant'] ?? 0,
            'not_applicable' => $stats['not_applicable'] ?? 0,
            'progress_percentage' => round($progress, 2),
        ];
    }

    public function getResponsesByStatus(int $assessmentId, string $status): Collection
    {
        return $this->model
            ->where('assessment_id', $assessmentId)
            ->where('status', $status)
            ->with(['control.domain', 'assignedTo'])
            ->get();
    }

    public function getUnassessedControls(int $assessmentId): Collection
    {
        return $this->model
            ->where('assessment_id', $assessmentId)
            ->where('status', 'not_assessed')
            ->with(['control.domain'])
            ->get();
    }

    public function initializeResponses(int $assessmentId): int
    {
        $controls = \App\Models\Control::where('is_active', true)->get();
        $count = 0;

        foreach ($controls as $control) {
            $exists = $this->model
                ->where('assessment_id', $assessmentId)
                ->where('control_id', $control->id)
                ->exists();

            if (!$exists) {
                $this->model->create([
                    'assessment_id' => $assessmentId,
                    'control_id' => $control->id,
                    'status' => 'not_assessed',
                    'maturity_level' => null,
                ]);
                $count++;
            }
        }

        return $count;
    }
}