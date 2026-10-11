<?php

namespace App\Reports;

class RiskRegisterReport extends AbstractReport
{
    public function key(): string { return 'risk_register'; }
    public function title(): string { return 'Risk Register'; }
    public function description(): string { return 'Full list of identified risks with severity.'; }
    public function icon(): string { return '⚠️'; }

    public function columns(): array
    {
        return ['Risk', 'Likelihood', 'Impact', 'Level', 'Owner', 'Status'];
    }

    public function rows(int $tenantId): array
    {
        return $this->repo->risks($tenantId)->map(fn ($r) => [
            'title' => $r->title ?? $r->name ?? '-',
            'likelihood' => $r->likelihood ?? '-',
            'impact' => $r->impact ?? '-',
            'level' => $r->risk_level ?? $r->level ?? '-',
            'owner' => $r->owner?->name ?? '-',
            'status' => $r->status ?? '-',
        ])->values()->toArray();
    }

    public function summary(int $tenantId): array
    {
        $risks = $this->repo->risks($tenantId);
        return [
            'Total Risks' => $risks->count(),
            'Critical' => $risks->where('risk_level', 'critical')->count(),
            'High' => $risks->where('risk_level', 'high')->count(),
            'Medium' => $risks->where('risk_level', 'medium')->count(),
            'Low' => $risks->where('risk_level', 'low')->count(),
        ];
    }
}