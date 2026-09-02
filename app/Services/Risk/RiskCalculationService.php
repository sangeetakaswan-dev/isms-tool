<?php

namespace App\Services\Risk;

use App\Models\RiskAssessment;

class RiskCalculationService
{
    public function calculateRiskScore(int $likelihood, int $impact): int
    {
        return $likelihood * $impact;
    }

    public function determineRiskLevel(int $score): string
    {
        if ($score <= 4)
            return 'low';
        if ($score <= 9)
            return 'medium';
        if ($score <= 16)
            return 'high';
        return 'critical';
    }

    /**
     * Calculate residual risk based on implemented controls.
     * This is a simplified example – adjust the reduction logic as per your methodology.
     */
    public function calculateResidualRisk(RiskAssessment $risk, array $controls): array
    {
        // Example: each implemented control reduces likelihood by 0.5 and impact by 0.5 (capped)
        $implementedCount = collect($controls)->where('status', 'implemented')->count();
        $reduction = $implementedCount * 0.5;

        $residualLikelihood = max(1, $risk->likelihood - (int) floor($reduction));
        $residualImpact = max(1, $risk->impact - (int) floor($reduction));

        $residualScore = $this->calculateRiskScore($residualLikelihood, $residualImpact);
        $residualLevel = $this->determineRiskLevel($residualScore);

        return [
            'residual_likelihood' => $residualLikelihood,
            'residual_impact' => $residualImpact,
            'residual_score' => $residualScore,
            'residual_level' => $residualLevel,
        ];
    }

    public function generateRiskHeatmap(array $risks): array
    {
        // 5x5 grid (Likelihood x Impact) ke liye risk counts ka data
        $heatmap = [];

        // Initialize empty grid with zeros
        for ($likelihood = 1; $likelihood <= 5; $likelihood++) {
            for ($impact = 1; $impact <= 5; $impact++) {
                $heatmap[$likelihood][$impact] = 0;
            }
        }

        // Risks ke through loop karke counts set karein
        foreach ($risks as $risk) {
            $likelihood = $risk['likelihood'] ?? $risk->likelihood ?? null;
            $impact = $risk['impact'] ?? $risk->impact ?? null;

            if ($likelihood >= 1 && $likelihood <= 5 && $impact >= 1 && $impact <= 5) {
                $heatmap[$likelihood][$impact]++;
            }
        }

        return $heatmap;
    }
}