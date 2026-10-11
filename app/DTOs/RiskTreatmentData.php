<?php

namespace App\DTOs;

readonly class RiskTreatmentData
{
    public function __construct(
        public int $riskId,
        public string $strategy,
        public string $action,
        public ?float $costEstimate,
        public ?string $dueDate,
        public ?int $assignedTo,
        public string $status = 'pending',
    ) {}

    public static function fromRequest(array $data, int $riskId): self
    {
        return new self(
            riskId:       $riskId,
            strategy:     $data['strategy'] ?? 'mitigate',
            action:       $data['action'],
            costEstimate: isset($data['cost_estimate']) ? (float) $data['cost_estimate'] : null,
             dueDate:      $data['due_date'] ?? now()->addDays(30)->toDateString(),
            assignedTo:   $data['assigned_to'] ?? null,
            status:       $data['status'] ?? 'pending',
        );
    }

    public function toArray(): array
    {
        return [
            'risk_id'       => $this->riskId,
            'strategy'      => $this->strategy,
            'action'        => $this->action,
            'cost_estimate' => $this->costEstimate,
            'due_date'      => $this->dueDate,
            'assigned_to'   => $this->assignedTo,
            'status'        => $this->status,
        ];
    }
}