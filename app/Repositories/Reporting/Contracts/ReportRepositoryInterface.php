<?php

namespace App\Repositories\Reporting\Contracts;

use App\Models\GeneratedReport;
use App\Models\ReportSnapshot;
use Illuminate\Support\Collection;

interface ReportRepositoryInterface
{
    public function controls(int $tenantId): Collection;
    public function soaEntries(int $tenantId): Collection;
    public function risks(int $tenantId): Collection;
    public function assets(int $tenantId): Collection;
    public function documents(int $tenantId): Collection;
    public function assessments(int $tenantId): Collection;

    public function snapshots(int $tenantId, string $type, int $limit = 12): Collection;
    public function storeSnapshot(int $tenantId, string $type, array $metrics, string $date): ReportSnapshot;
    public function storeGeneratedReport(array $data): GeneratedReport;
    public function reportHistory(int $tenantId, int $limit = 25): Collection;
}