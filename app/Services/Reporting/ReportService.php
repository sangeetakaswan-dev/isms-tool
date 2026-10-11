<?php

namespace App\Services\Reporting;

use App\DTOs\ReportData;
use App\Reports\Contracts\ReportInterface;
use App\Reports\ReportRegistry;

class ReportService
{
    public function __construct(
        private ReportRegistry $registry
    ) {
    }

    public function availableTypes(): array
    {
        return collect($this->registry->all())
            ->map(fn(ReportInterface $r) => [
                'key' => $r->key(),
                'title' => $r->title(),
                'description' => $r->description(),
                'icon' => $r->icon(),
            ])
            ->all();
    }

    public function generate(string $type, ?int $tenantId): ReportData
    {
        $tenantId = $tenantId ?? 0;
        $report = $this->registry->make($type);

        return new ReportData(
            key: $report->key(),
            title: $report->title(),
            description: $report->description(),
            columns: $report->columns(),
            rows: $report->rows($tenantId),
            summary: $report->summary($tenantId),
            meta: ['generated_at' => now()->toDateTimeString()],
        );
    }

    public function report(string $type): ReportInterface
    {
        return $this->registry->make($type);
    }
}