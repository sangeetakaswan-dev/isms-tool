<?php

namespace App\Services\Reporting;

use App\DTOs\DashboardMetricsData;
use App\Repositories\Reporting\Contracts\ReportRepositoryInterface;

class DashboardService
{
    public function __construct(
        private ReportRepositoryInterface $repo
    ) {
    }

    public function getDashboardData(?int $tenantId): DashboardMetricsData
    {
        // ---------- Null guard ----------
        if (!$tenantId) {
            return new DashboardMetricsData(
                complianceScore: 0,
                domainCompliance: [],
                maturityDistribution: [],
                riskSummary: ['critical' => 0, 'high' => 0, 'medium' => 0, 'low' => 0],
                riskHeatmap: [],
                documentStatus: [],
                trend: [],
                counts: ['controls' => 0, 'risks' => 0, 'documents' => 0, 'assets' => 0],
            );
        }

        // ---------- Actual metrics ----------
        $controls = $this->repo->controls($tenantId);
        $risks = $this->repo->risks($tenantId);
        $docs = $this->repo->documents($tenantId);
        $assets = $this->repo->assets($tenantId);

        $applicable = $controls->where('applicable', true)->count();
        $implemented = $controls->where('applicable', true)->where('implemented', true)->count();
        $score = $applicable > 0 ? round(($implemented / $applicable) * 100, 2) : 0;

        // Fix: Domain object ki jagah uski property nikal rahe hain
        $domainCompliance = $controls->groupBy('domain_id')->map(function ($group, $domainId) {
            $domainModel = \App\Models\Domain::find($domainId);
            $domainName = $domainModel ? $domainModel->name : 'Unassigned';

            $a = $group->where('applicable', true)->count();
            $i = $group->where('applicable', true)->where('implemented', true)->count();
            return [
                'domain' => $domainName,
                'score' => $a > 0 ? round(($i / $a) * 100, 2) : 0,
                'applicable' => $a,
                'implemented' => $i,
            ];
        })->values()->toArray();

        $maturityDistribution = $this->repo->assessments($tenantId)
            ->groupBy('maturity_level')
            ->map(fn($g, $lvl) => ['level' => (int) $lvl, 'count' => $g->count()])
            ->values()->toArray();

        // Fix: Total aur Open add kiya
        $riskSummary = [
            'total' => $risks->count(),
            'open' => $risks->whereNotIn('status', ['closed'])->count(),
            'critical' => $risks->where('risk_level', 'critical')->count(),
            'high' => $risks->where('risk_level', 'high')->count(),
            'medium' => $risks->where('risk_level', 'medium')->count(),
            'low' => $risks->where('risk_level', 'low')->count(),
        ];

        $heatmap = [];
        foreach ($risks as $r) {
            $l = (int) ($r->likelihood ?? 0);
            $i = (int) ($r->impact ?? 0);
            if ($l && $i) {
                $heatmap["{$l}-{$i}"] = ($heatmap["{$l}-{$i}"] ?? 0) + 1;
            }
        }

        $documentStatus = $docs->groupBy('status')->map(fn($g) => $g->count())->toArray();

        $trend = $this->repo->snapshots($tenantId, 'compliance_score', 12)
            ->map(fn($s) => [
                'date' => $s->snapshot_date->format('Y-m-d'),
                'score' => $s->metrics['score'] ?? 0,
            ])->values()->toArray();

        // Persist today's snapshot
        $this->repo->storeSnapshot(
            $tenantId,
            'compliance_score',
            ['score' => $score],
            now()->toDateString()
        );

        return new DashboardMetricsData(
            complianceScore: $score,
            domainCompliance: $domainCompliance,
            maturityDistribution: $maturityDistribution,
            riskSummary: $riskSummary,
            riskHeatmap: $heatmap,
            documentStatus: $documentStatus,
            trend: $trend,
            counts: [
                'controls' => $controls->count(),
                'risks' => $risks->count(),
                'documents' => $docs->count(),
                'assets' => $assets->count(),
            ],
        );
    }
}