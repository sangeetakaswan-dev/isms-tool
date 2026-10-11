<?php

namespace App\Services\Risk;

use App\DTOs\RiskData;
use App\Models\Risk;
use App\Repositories\Contracts\RiskRepositoryInterface;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class RiskService
{
    public function __construct(
        private readonly RiskRepositoryInterface $repository,
        private readonly NotificationService $notifications,
    ) {}

    public function create(RiskData $data): Risk
    {
        $risk = $this->repository->create($data->toArray());

        Log::info('Risk created', ['risk_id' => $risk->id, 'tenant' => $risk->tenant_id]);

        if ($risk->owner_id) {
            $this->notifications->notifyRiskAssigned($risk);
        }

        return $risk;
    }

    public function update(Risk $risk, RiskData $data): Risk
    {
        $previousOwner = $risk->owner_id;
        $updated = $this->repository->update($risk, $data->toArray());

        if ($updated->owner_id && $updated->owner_id !== $previousOwner) {
            $this->notifications->notifyRiskAssigned($updated);
        }

        return $updated;
    }

    public function delete(Risk $risk): bool
    {
        return $this->repository->delete($risk);
    }

    public function find(int $id): ?Risk
    {
        return $this->repository->find($id);
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function heatmap(): array
    {
        return $this->repository->heatmapData();
    }

    public function summary(): array
    {
        $all = $this->repository->all();

        return [
            'total'      => $all->count(),
            'by_level'   => $all->groupBy('risk_level')->map->count()->toArray(),
            'by_status'  => $all->groupBy('status')->map->count()->toArray(),
            'critical'   => $all->where('risk_level', 'critical')->count(),
            'high'       => $all->where('risk_level', 'high')->count(),
            'open'       => $all->whereNotIn('status', ['closed'])->count(),
        ];
    }

    public function topRisks(int $limit = 10): Collection
    {
        return $this->repository->topRisks($limit);
    }
}