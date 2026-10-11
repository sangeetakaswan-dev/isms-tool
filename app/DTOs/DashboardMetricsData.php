<?php

namespace App\DTOs;

readonly class DashboardMetricsData
{
    public function __construct(
        public float $complianceScore,
        public array $domainCompliance,
        public array $maturityDistribution,
        public array $riskSummary,
        public array $riskHeatmap,
        public array $documentStatus,
        public array $trend,
        public array $counts,
    ) {
    }

    public function toArray(): array
    {
        return [
            'compliance_score' => $this->complianceScore,
            'domain_compliance' => $this->domainCompliance,
            'maturity_distribution' => $this->maturityDistribution,
            'risk_summary' => $this->riskSummary,
            'risk_heatmap' => $this->riskHeatmap,
            'document_status' => $this->documentStatus,
            'trend' => $this->trend,
            'counts' => $this->counts,
        ];
    }
}