<?php

namespace App\Reports;

class ExecutiveSummaryReport extends AbstractReport
{
    public function key(): string { return 'executive_summary'; }
    public function title(): string { return 'Executive Summary'; }
    public function description(): string { return 'Compliance posture overview for management.'; }
    public function icon(): string { return '📊'; }

    public function columns(): array
    {
        return ['Domain', 'Total Controls', 'Applicable', 'Implemented', 'Score %'];
    }

    public function rows(int $tenantId): array
    {
        $controls = $this->repo->controls($tenantId);
        return $controls->groupBy('domain')->map(function ($group, $domain) {
            $applicable = $group->where('applicable', true)->count();
            $implemented = $group->where('applicable', true)->where('implemented', true)->count();
            return [
                'domain' => $domain,
                'total' => $group->count(),
                'applicable' => $applicable,
                'implemented' => $implemented,
                'score' => $applicable > 0 ? round(($implemented / $applicable) * 100, 2) : 0,
            ];
        })->values()->toArray();
    }

    public function summary(int $tenantId): array
    {
        $controls = $this->repo->controls($tenantId);
        $applicable = $controls->where('applicable', true)->count();
        $implemented = $controls->where('applicable', true)->where('implemented', true)->count();
        $score = $applicable > 0 ? round(($implemented / $applicable) * 100, 2) : 0;

        return [
            'Compliance Score' => $score . '%',
            'Total Controls' => $controls->count(),
            'Applicable Controls' => $applicable,
            'Implemented Controls' => $implemented,
            'Open Risks' => $this->repo->risks($tenantId)->where('status', '!=', 'closed')->count(),
            'Total Documents' => $this->repo->documents($tenantId)->count(),
        ];
    }
}