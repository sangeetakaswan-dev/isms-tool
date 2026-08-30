<?php

namespace App\Services\Compliance;

use App\Models\Assessment;
use App\Models\Domain;
use App\Repositories\Contracts\AssessmentResponseRepositoryInterface;
use Illuminate\Support\Collection;

class ComplianceCalculator
{
    public function __construct(
        private readonly AssessmentResponseRepositoryInterface $responseRepository,
    ) {
    }

    /**
     * Calculate overall compliance score.
     */
    public function calculateOverallCompliance(Assessment $assessment): float
    {
        $stats = $this->responseRepository->getProgressStats($assessment->id);

        $applicableTotal = $stats['total'] - $stats['not_applicable'];

        if ($applicableTotal <= 0) {
            return 0;
        }

        $compliantCount = $stats['compliant'];
        $partialWeight = $stats['partially_compliant'] * 0.5; // 50% credit for partial

        $score = (($compliantCount + $partialWeight) / $applicableTotal) * 100;

        return round($score, 2);
    }

    /**
     * Calculate compliance for a specific domain.
     */
    public function calculateDomainCompliance(Assessment $assessment, Domain $domain): array
    {
        $responses = $this->responseRepository->getByDomain($assessment->id, $domain->id);

        $stats = $this->calculateStats($responses);

        return [
            'domain_id' => $domain->id,
            'domain_code' => $domain->code,
            'domain_name' => $domain->name,
            'total_controls' => $stats['total'],
            'applicable_controls' => $stats['total'] - $stats['not_applicable'],
            'compliant' => $stats['compliant'],
            'non_compliant' => $stats['non_compliant'],
            'partially_compliant' => $stats['partially_compliant'],
            'not_applicable' => $stats['not_applicable'],
            'not_assessed' => $stats['not_assessed'],
            'compliance_percentage' => $this->calculatePercentage($stats),
            'maturity_average' => $this->calculateAverageMaturity($responses),
        ];
    }

    /**
     * Calculate compliance across all domains.
     */
    public function calculateAllDomainCompliance(Assessment $assessment): Collection
    {
        $domains = Domain::orderBy('sort_order')->get();
        $results = collect();

        foreach ($domains as $domain) {
            $results->push($this->calculateDomainCompliance($assessment, $domain));
        }

        return $results;
    }

    /**
     * Calculate gap analysis.
     */
    public function calculateGapAnalysis(Assessment $assessment): array
    {
        $responses = $this->responseRepository->getAllForAssessment($assessment->id);

        $gaps = $responses->filter(function ($response) {
            return in_array($response->status, ['non_compliant', 'partially_compliant']);
        })->map(function ($response) {
            return [
                'control_id' => $response->control->control_id,
                'control_title' => $response->control->title,
                'domain' => $response->control->domain->name,
                'status' => $response->status,
                'gap_description' => $response->gap_description,
                'evidence_notes' => $response->evidence_notes,
                'assigned_to' => $response->assignedTo?->name,
                'due_date' => $response->due_date,
            ];
        })->values();

        return [
            'total_gaps' => $gaps->count(),
            'critical_gaps' => $gaps->where('status', 'non_compliant')->count(),
            'partial_gaps' => $gaps->where('status', 'partially_compliant')->count(),
            'gaps' => $gaps->toArray(),
        ];
    }

    /**
     * Calculate maturity distribution.
     */
    public function calculateMaturityDistribution(Assessment $assessment): array
    {
        $responses = $this->responseRepository->getAllForAssessment($assessment->id);

        $distribution = [0, 0, 0, 0, 0, 0]; // Levels 0-5

        foreach ($responses as $response) {
            if ($response->maturity_level !== null && $response->status !== 'not_applicable') {
                $distribution[$response->maturity_level]++;
            }
        }

        return [
            'level_0' => $distribution[0],
            'level_1' => $distribution[1],
            'level_2' => $distribution[2],
            'level_3' => $distribution[3],
            'level_4' => $distribution[4],
            'level_5' => $distribution[5],
            'total' => array_sum($distribution),
        ];
    }

    /**
     * Calculate effectiveness score.
     */
    public function calculateEffectivenessScore(Assessment $assessment): float
    {
        $maturityData = $this->calculateMaturityDistribution($assessment);
        $total = $maturityData['total'];

        if ($total === 0) {
            return 0;
        }

        $weightedSum =
            $maturityData['level_1'] * 1 +
            $maturityData['level_2'] * 2 +
            $maturityData['level_3'] * 3 +
            $maturityData['level_4'] * 4 +
            $maturityData['level_5'] * 5;

        $maxPossible = $total * 5;

        return round(($weightedSum / $maxPossible) * 100, 2);
    }

    /**
     * Calculate readiness for certification.
     */
    public function calculateCertificationReadiness(Assessment $assessment): array
    {
        $complianceScore = $this->calculateOverallCompliance($assessment);
        $maturityData = $this->calculateMaturityDistribution($assessment);
        $gapAnalysis = $this->calculateGapAnalysis($assessment);

        $readiness = 'not_ready';
        $recommendations = [];

        if ($complianceScore >= 90 && $gapAnalysis['critical_gaps'] === 0) {
            $readiness = 'ready';
            $recommendations[] = 'Organization meets minimum certification requirements';
        } elseif ($complianceScore >= 75 && $gapAnalysis['critical_gaps'] <= 5) {
            $readiness = 'almost_ready';
            $recommendations[] = 'Address critical gaps to achieve certification readiness';
        } else {
            $readiness = 'not_ready';
            $recommendations[] = 'Significant work required to meet certification requirements';
        }

        if ($maturityData['level_0'] > 0 || $maturityData['level_1'] > 0) {
            $recommendations[] = 'Focus on controls with maturity level 0-1 (initial/ad-hoc)';
        }

        if ($complianceScore < 80) {
            $recommendations[] = 'Overall compliance below 80% threshold';
        }

        return [
            'readiness_status' => $readiness,
            'compliance_score' => $complianceScore,
            'critical_gaps' => $gapAnalysis['critical_gaps'],
            'total_gaps' => $gapAnalysis['total_gaps'],
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Calculate statistics from responses collection.
     */
    protected function calculateStats(Collection $responses): array
    {
        $stats = [
            'total' => $responses->count(),
            'compliant' => 0,
            'non_compliant' => 0,
            'partially_compliant' => 0,
            'not_applicable' => 0,
            'not_assessed' => 0,
        ];

        foreach ($responses as $response) {
            $stats[$response->status] = ($stats[$response->status] ?? 0) + 1;
        }

        return $stats;
    }

    /**
     * Calculate compliance percentage.
     */
    protected function calculatePercentage(array $stats): float
    {
        $applicableTotal = $stats['total'] - $stats['not_applicable'];

        if ($applicableTotal <= 0) {
            return 0;
        }

        $compliantCount = $stats['compliant'];
        $partialWeight = $stats['partially_compliant'] * 0.5;

        return round((($compliantCount + $partialWeight) / $applicableTotal) * 100, 2);
    }

    /**
     * Calculate average maturity from responses.
     */
    protected function calculateAverageMaturity(Collection $responses): float
    {
        $assessed = $responses->filter(function ($response) {
            return $response->maturity_level !== null &&
                $response->status !== 'not_applicable' &&
                $response->status !== 'not_assessed';
        });

        if ($assessed->isEmpty()) {
            return 0;
        }

        return round($assessed->avg('maturity_level'), 2);
    }
}