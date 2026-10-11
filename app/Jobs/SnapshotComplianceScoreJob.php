<?php

namespace App\Jobs;

use App\Models\ReportSnapshot;
use App\Models\Tenant;
use App\Services\Reporting\DashboardService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SnapshotComplianceScoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(DashboardService $dashboard): void
    {
        foreach (Tenant::all() as $tenant) {
            try {
                $metrics = $dashboard->getDashboardData($tenant->id);

                ReportSnapshot::updateOrCreate(
                    [
                        'tenant_id'     => $tenant->id,
                        'report_type'   => 'compliance_score',
                        'snapshot_date' => now()->toDateString(),
                    ],
                    [
                        'metrics' => [
                            'compliance_score' => $metrics->complianceScore,
                            'domain_breakdown' => $metrics->domainCompliance,
                            'risk_summary'     => $metrics->riskSummary,
                        ],
                    ]
                );
            } catch (\Throwable $e) {
                \Log::error("Snapshot failed for tenant {$tenant->id}", ['error' => $e->getMessage()]);
            }
        }
    }
}