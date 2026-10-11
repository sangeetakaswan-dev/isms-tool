<?php

namespace App\Repositories;

use App\Models\Risk;
use App\Repositories\Contracts\RiskRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RiskRepository implements RiskRepositoryInterface
{
    public function find(int $id): ?Risk
    {
        return Risk::with(['asset', 'owner', 'treatmentPlans'])->find($id);
    }

    public function create(array $data): Risk
    {
        return Risk::create($data);
    }

    public function update(Risk $risk, array $data): Risk
    {
        $risk->update($data);
        return $risk->fresh();
    }

    public function delete(Risk $risk): bool
    {
        return $risk->delete();
    }

    public function all(): Collection
    {
        return Risk::with(['asset', 'owner'])->orderByDesc('risk_score')->get();
    }

    public function byLevel(string $level): Collection
    {
        return Risk::with(['asset', 'owner'])->byLevel($level)->get();
    }

    public function byStatus(string $status): Collection
    {
        return Risk::with(['asset', 'owner'])->byStatus($status)->get();
    }

    public function heatmapData(): array
    {
        $rows = Risk::select('likelihood', 'impact', DB::raw('COUNT(*) as count'))
            ->groupBy('likelihood', 'impact')
            ->get();

        $grid = [];
        for ($l = 1; $l <= 5; $l++) {
            for ($i = 1; $i <= 5; $i++) {
                $grid[$l][$i] = 0;
            }
        }

        foreach ($rows as $row) {
            $grid[$row->likelihood][$row->impact] = (int) $row->count;
        }

        return $grid;
    }

    public function topRisks(int $limit = 10): Collection
    {
        return Risk::with(['asset', 'owner'])
            ->open()
            ->orderByDesc('risk_score')
            ->limit($limit)
            ->get();
    }

    public function overdueTreatments(): Collection
    {
        return Risk::with(['treatmentPlans' => function ($q) {
            $q->where('status', '!=', 'completed')
              ->whereDate('due_date', '<', now());
        }])->get();
    }
}