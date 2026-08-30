<?php

namespace App\DTOs;

class AssessmentResponseData
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly int $assessmentId,
        public readonly int $controlId,
        public readonly ?string $status = null,
        public readonly ?int $maturityLevel = null,
        public readonly ?string $evidenceNotes = null,
        public readonly ?string $gapDescription = null,
        public readonly ?int $assignedTo = null,
        public readonly ?string $dueDate = null,
        public readonly ?int $assessedBy = null,
        public readonly ?string $assessedAt = null,
        public readonly ?string $notApplicableReason = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            assessmentId: $data['assessment_id'],
            controlId: $data['control_id'],
            status: $data['status'] ?? null,
            maturityLevel: $data['maturity_level'] ?? null,
            evidenceNotes: $data['evidence_notes'] ?? null,
            gapDescription: $data['gap_description'] ?? null,
            assignedTo: $data['assigned_to'] ?? null,
            dueDate: $data['due_date'] ?? null,
            assessedBy: $data['assessed_by'] ?? null,
            assessedAt: $data['assessed_at'] ?? null,
            notApplicableReason: $data['not_applicable_reason'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'assessment_id' => $this->assessmentId,
            'control_id' => $this->controlId,
            'status' => $this->status,
            'maturity_level' => $this->maturityLevel,
            'evidence_notes' => $this->evidenceNotes,
            'gap_description' => $this->gapDescription,
            'assigned_to' => $this->assignedTo,
            'due_date' => $this->dueDate,
            'assessed_by' => $this->assessedBy,
            'assessed_at' => $this->assessedAt,
            'not_applicable_reason' => $this->notApplicableReason,
        ], fn($value) => $value !== null);
    }
}