<?php

namespace App\Livewire;

use App\Models\RiskAssessment;
use App\Models\RiskTreatmentPlan;
use Livewire\Component;

class RiskDashboard extends Component
{
    public $risks;
    public $heatmapData;
    public $criticalRisks;
    public $treatmentPending;

    public function mount()
    {
        $this->loadData();
    }

    private function loadData()
    {
        $this->risks = RiskAssessment::with('asset', 'treatmentPlans')->get();
        $this->heatmapData = (new \App\Services\Risk\RiskCalculationService())->generateRiskHeatmap($this->risks->toArray());
        $this->criticalRisks = $this->risks->where('risk_level', 'critical');
        $this->treatmentPending = RiskTreatmentPlan::where('status', 'pending')->count();
    }

    public function getRiskDistributionProperty()
    {
        return $this->risks->groupBy('risk_level')->map->count();
    }

    public function getTreatmentStatsProperty()
    {
        return $this->risks->groupBy('treatment')->map->count()->toArray();
    }

    public function getTrendDataProperty()
    {
        // Sabhi risks ko fetch karke PHP me group karein (database-specific functions avoid karein)
        return RiskAssessment::all()
            ->groupBy(fn($risk) => $risk->created_at->format('Y-m'))
            ->map->count()
            ->sortKeys()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.risk-dashboard');
    }
}