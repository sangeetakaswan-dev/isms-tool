<?php

namespace App\DTOs;

use Carbon\Carbon;

class AssessmentData
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?int $tenantId = null,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?string $scope = null,
        public readonly ?string $startDate = null,
        public readonly ?string $targetDate = null,
        public readonly ?string $completedDate = null,
        public readonly ?string $status = null,
        public readonly ?int $progressPercentage = null,
        public readonly ?int $leadAssessorId = null,
        public readonly ?array $teamMembers = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            tenantId: $data['tenant_id'] ?? null,
            name: $data['name'] ?? null,
            description: $data['description'] ?? null,
            scope: $data['scope'] ?? null,
            startDate: $data['start_date'] ?? null,
            targetDate: $data['target_date'] ?? null,
            completedDate: $data['completed_date'] ?? null,
            status: $data['status'] ?? null,
            progressPercentage: $data['progress_percentage'] ?? null,
            leadAssessorId: $data['lead_assessor_id'] ?? null,
            teamMembers: $data['team_members'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'tenant_id' => $this->tenantId,
            'name' => $this->name,
            'description' => $this->description,
            'scope' => $this->scope,
            'start_date' => $this->startDate,
            'target_date' => $this->targetDate,
            'completed_date' => $this->completedDate,
            'status' => $this->status,
            'progress_percentage' => $this->progressPercentage,
            'lead_assessor_id' => $this->leadAssessorId,
            'team_members' => $this->teamMembers,
        ], fn($value) => $value !== null);
    }
}