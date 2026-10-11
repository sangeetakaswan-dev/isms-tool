<?php

namespace App\Repositories\Reporting;

use App\Models\Assessment;
use App\Models\Control;
use App\Models\Domain;
use App\Models\Document;
use App\Models\GeneratedReport;
use App\Models\ReportSnapshot;
use App\Repositories\Reporting\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Risk;
use App\Models\SoAEntry;

class ReportRepository implements ReportRepositoryInterface
{
    public function controls(int $tenantId): Collection
    {
        if (!class_exists(Control::class)) {
            return collect();
        }

        return $this->queryWithTenant(Control::class, $tenantId);
    }

    public function soaEntries(int $tenantId): Collection
    {
        $class = 'App\\Models\\SoaEntry';

        if (!class_exists($class)) {
            return collect();
        }

        return $this->queryWithTenant($class, $tenantId, ['control']);
    }

    public function risks(int $tenantId): Collection
    {
        $class = 'App\\Models\\Risk';

        if (!class_exists($class)) {
            return collect();
        }

        return $this->queryWithTenant($class, $tenantId, ['owner']);
    }

    public function assets(int $tenantId): Collection
    {
        $class = 'App\\Models\\Asset';

        if (!class_exists($class)) {
            return collect();
        }

        return $this->queryWithTenant($class, $tenantId, ['owner']);
    }

    public function documents(int $tenantId): Collection
    {
        if (!class_exists(Document::class)) {
            return collect();
        }

        return $this->queryWithTenant(Document::class, $tenantId);
    }

    public function assessments(int $tenantId): Collection
    {
        if (!class_exists(Assessment::class)) {
            return collect();
        }

        return $this->queryWithTenant(Assessment::class, $tenantId);
    }

    public function snapshots(int $tenantId, string $type, int $limit = 12): Collection
    {
        return ReportSnapshot::where('tenant_id', $tenantId)
            ->where('report_type', $type)
            ->orderBy('snapshot_date')
            ->limit($limit)
            ->get();
    }

    public function storeSnapshot(int $tenantId, string $type, array $metrics, string $date): ReportSnapshot
    {
        return ReportSnapshot::updateOrCreate(
            ['tenant_id' => $tenantId, 'report_type' => $type, 'snapshot_date' => $date],
            ['metrics' => $metrics]
        );
    }

    public function storeGeneratedReport(array $data): GeneratedReport
    {
        return GeneratedReport::create($data);
    }

    public function reportHistory(int $tenantId, int $limit = 25): Collection
    {
        return GeneratedReport::with('user')
            ->where('tenant_id', $tenantId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Safe tenant-scoped query — agar table me tenant_id nahi hai to sab return karta hai,
     * warna tenant filter lagata hai.
     */
    protected function queryWithTenant(string $modelClass, int $tenantId, array $with = []): Collection
    {
        if ($tenantId <= 0) {
            return collect();
        }

        $model = new $modelClass;
        $query = $modelClass::query();

        if (!empty($with)) {
            $query->with($with);
        }

        // Agar tenant_id column hai to filter lagao, warna skip
        if (\Schema::hasColumn($model->getTable(), 'tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->get();
    }

    public function getRisks(): Collection
    {
        if (!class_exists(Risk::class)) {
            return collect();
        }
        return Risk::with(['asset', 'owner'])->orderByDesc('risk_score')->get();
    }

    public function getSoAEntries(): Collection
    {
        if (!class_exists(SoAEntry::class)) {
            return collect();
        }
        return SoAEntry::with('control')->get();
    }

}