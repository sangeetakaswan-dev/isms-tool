<?php

namespace Tests\Feature\Risk;

use App\Jobs\SnapshotComplianceScoreJob;
use App\Models\ReportSnapshot;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ScheduledSnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_snapshot_job_creates_daily_snapshot(): void
    {
        $tenant = Tenant::factory()->create();

        (new SnapshotComplianceScoreJob())->handle(app(\App\Services\Reporting\DashboardService::class));
        $this->assertDatabaseHas('report_snapshots', [
            'tenant_id' => $tenant->id,
            'report_type' => 'compliance_score',
            'snapshot_date' => now()->toDateString() . ' 00:00:00',  // ← datetime format

        ]);
    }
}