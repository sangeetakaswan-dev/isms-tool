<?php

namespace App\Reports;

class SoaStatusReport extends AbstractReport
{
    public function key(): string { return 'soa_status'; }
    public function title(): string { return 'SoA Status'; }
    public function description(): string { return 'Statement of Applicability per control.'; }
    public function icon(): string { return '📋'; }

    public function columns(): array
    {
        return ['Control Ref', 'Control Name', 'Applicable', 'Justification', 'Status'];
    }

    public function rows(int $tenantId): array
    {
        return $this->repo->soaEntries($tenantId)->map(fn ($s) => [
            'ref' => $s->control?->reference ?? '-',
            'name' => $s->control?->name ?? '-',
            'applicable' => $s->applicable ? 'Yes' : 'No',
            'justification' => $s->justification ?? '-',
            'status' => $s->status ?? '-',
        ])->values()->toArray();
    }
}