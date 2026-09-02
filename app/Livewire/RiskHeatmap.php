<?php

namespace App\Livewire;

use App\Models\RiskAssessment;
use Livewire\Component;

class RiskHeatmap extends Component
{
    public $risks;
    public $heatmapData = [];
    public $selectedCell = null;

    protected $listeners = ['filterByHeatmapCell'];

    public function mount($risks = null)
    {
        // Agar parent se risks pass kiye gaye hain to use karo, warna khud fetch karo
        $this->risks = $risks ?? RiskAssessment::with('asset')->get();
        $this->generateHeatmapData();
    }

    public function generateHeatmapData()
    {
        $data = [];
        for ($likelihood = 1; $likelihood <= 5; $likelihood++) {
            for ($impact = 1; $impact <= 5; $impact++) {
                $count = $this->risks
                    ->where('likelihood', $likelihood)
                    ->where('impact', $impact)
                    ->count();
                $data[$likelihood][$impact] = $count;
            }
        }
        $this->heatmapData = $data;
    }

    public function filterByHeatmapCell($likelihood, $impact)
    {
        $this->selectedCell = ['likelihood' => $likelihood, 'impact' => $impact];
        $this->risks = RiskAssessment::where('likelihood', $likelihood)
            ->where('impact', $impact)
            ->with('asset')
            ->get();
        $this->emit('riskListUpdated', $this->risks);
    }

    public function render()
    {
        return view('livewire.risk-heatmap');
    }
}