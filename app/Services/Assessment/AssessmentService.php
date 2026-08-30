<?php

namespace App\Services\Assessment;

use App\DTOs\AssessmentData;
use App\Models\Assessment;
use App\Models\Control;
use App\Models\AssessmentResponse;
use App\Repositories\Contracts\AssessmentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


class AssessmentService
{
    public function __construct(
        private readonly AssessmentRepositoryInterface $assessmentRepository
    ) {
    }

    public function createAssessment(AssessmentData $data): Assessment
    {
        try {
            DB::beginTransaction();

            $assessment = $this->assessmentRepository->create($data->toArray());

            Log::info('Assessment created', [
                'assessment_id' => $assessment->id,
                'tenant_id' => $assessment->tenant_id,
                'user_id' => auth()->id(),
                'name' => $assessment->name,
            ]);

            DB::commit();

            return $assessment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create assessment', [
                'error' => $e->getMessage(),
                'data' => $data->toArray(),
            ]);
            throw $e;
        }
    }
    public function initializeResponses(Assessment $assessment): void
    {
        $controls = Control::active()->get();

        $responses = $controls->map(function ($control) use ($assessment) {
            return [
                'assessment_id' => $assessment->id,
                'control_id' => $control->id,
                'status' => 'not_assessed',
                'maturity_level' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        AssessmentResponse::insert($responses);
    }

    public function updateAssessment(int $assessmentId, AssessmentData $data): Assessment
    {
        $assessment = $this->assessmentRepository->findOrFail($assessmentId);
        $assessment->update($data->toArray());
        return $assessment;
    }

    public function calculateProgress(Assessment $assessment): int
    {
        return $this->assessmentRepository->calculateProgress($assessment);
    }

    public function validateCompletion(Assessment $assessment): bool
    {
        // Check if all applicable controls are assessed
        $unassessedCount = $assessment->responses()
            ->where('status', 'not_assessed')
            ->count();

        return $unassessedCount === 0;
    }

    public function getPaginatedAssessments(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->assessmentRepository->getPaginated($perPage, $filters);
    }

    /**
     * Get team members for assessment.
     */
    public function getTeamMembers(Assessment $assessment): \Illuminate\Support\Collection
    {
        return DB::table('assessment_team')
            ->join('users', 'users.id', '=', 'assessment_team.user_id')
            ->where('assessment_team.assessment_id', $assessment->id)
            ->select('users.*', 'assessment_team.role as team_role')
            ->get();
    }

}