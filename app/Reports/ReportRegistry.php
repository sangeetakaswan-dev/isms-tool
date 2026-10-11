<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Repositories\Reporting\Contracts\ReportRepositoryInterface;

class ReportRegistry
{
    /** @var array<string, class-string<ReportInterface>> */
    protected array $map = [
        'executive_summary' => ExecutiveSummaryReport::class,
        'gap_analysis' => GapAnalysisReport::class,
        'risk_register' => RiskRegisterReport::class,
        'soa_status' => SoaStatusReport::class,
        'document_compliance' => DocumentComplianceReport::class,
        'asset_inventory' => AssetInventoryReport::class,
    ];

    public function __construct(protected ReportRepositoryInterface $repo) {}

    public function make(string $key): ReportInterface
    {
        if (! isset($this->map[$key])) {
            throw new \InvalidArgumentException("Unknown report type: {$key}");
        }
        return new $this->map[$key]($this->repo);
    }

    /** @return array<string, ReportInterface> */
    public function all(): array
    {
        return collect($this->map)
            ->mapWithKeys(fn ($cls, $key) => [$key => new $cls($this->repo)])
            ->all();
    }

    public function keys(): array
    {
        return array_keys($this->map);
    }
}