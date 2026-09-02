<?php

namespace App\Services\Risk;

use App\DTOs\RiskData;
use App\Models\RiskAssessment;
use App\Repositories\RiskRepository;
use Illuminate\Support\Facades\DB;

class RiskService
{
    public function __construct(
        private RiskRepository $riskRepository,
        private RiskCalculationService $riskCalculationService
    ) {
    }

    public function createRisk(RiskData $data): RiskAssessment
    {
        return DB::transaction(function () use ($data) {
            $risk = $this->riskRepository->create($data->toArray());
            // If treatment or residual data provided, update residual
            if (isset($data->residual_likelihood) && isset($data->residual_impact)) {
                $this->updateResidualRisk($risk, $data->residual_likelihood, $data->residual_impact);
            }
            return $risk;
        });
    }

    public function updateRisk(int $id, RiskData $data): RiskAssessment
    {
        $risk = $this->riskRepository->find($id);
        $risk->update($data->toArray());
        if (isset($data->residual_likelihood) && isset($data->residual_impact)) {
            $this->updateResidualRisk($risk, $data->residual_likelihood, $data->residual_impact);
        }
        return $risk;
    }

    public function calculateRiskLevel(RiskAssessment $risk): string
    {
        return $this->riskCalculationService->determineRiskLevel($risk->risk_score);
    }

    public function applyTreatment(RiskAssessment $risk, string $treatment): void
    {
        $risk->treatment = $treatment;
        $risk->save();
    }

    public function reviewRisk(RiskAssessment $risk, array $updates): void
    {
        $risk->update($updates);
        $risk->review_date = now();
        $risk->save();
    }

    private function updateResidualRisk(RiskAssessment $risk, int $likelihood, int $impact): void
    {
        $score = $this->riskCalculationService->calculateRiskScore($likelihood, $impact);
        $level = $this->riskCalculationService->determineRiskLevel($score);
        $risk->residual_likelihood = $likelihood;
        $risk->residual_impact = $impact;
        $risk->residual_score = $score;
        $risk->residual_level = $level;
        $risk->save();
    }
}
