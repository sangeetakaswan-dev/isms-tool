<?php

namespace App\Repositories\Contracts;

use App\Models\Risk;
use Illuminate\Support\Collection;

interface RiskRepositoryInterface
{
    public function find(int $id): ?Risk;
    public function create(array $data): Risk;
    public function update(Risk $risk, array $data): Risk;
    public function delete(Risk $risk): bool;
    public function all(): Collection;
    public function byLevel(string $level): Collection;
    public function byStatus(string $status): Collection;
    public function heatmapData(): array;
    public function topRisks(int $limit = 10): Collection;
    public function overdueTreatments(): Collection;
}