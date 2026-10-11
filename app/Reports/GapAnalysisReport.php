<?php

namespace App\Reports;

class GapAnalysisReport extends AbstractReport
{
    public function key(): string { return 'gap_analysis'; }
    public function title(): string { return 'Gap Analysis'; }
    public function description(): string { return 'Applicable controls that are not yet implemented.'; }
    public function icon(): string { return '🧩'; }

    public function columns(): array
    {
        return ['Control Ref', 'Control Name', 'Domain', 'Status'];
    }

    public function rows(int $tenantId): array
    {
        return $this->repo->controls($tenantId)
            ->where('applicable', true)
            ->where('implemented', false)
            ->map(fn ($c) => [
                'ref' => $c->reference ?? $c->code ?? '-',
                'name' => $c->name ?? $c->title ?? '-',
                'domain' => $c->domain ?? '-',
                'status' => 'Not Implemented',
            ])->values()->toArray();
    }

    public function summary(int $tenantId): array
    {
        $gaps = $this->repo->controls($tenantId)->where('applicable', true)->where('implemented', false)->count();
        return ['Total Gaps' => $gaps];
    }
}