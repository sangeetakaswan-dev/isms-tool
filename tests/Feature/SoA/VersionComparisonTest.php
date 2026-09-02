<?php

namespace Tests\Feature\SoA;

use App\Models\Assessment;
use App\Models\Control;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Compliance\StatementOfApplicabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VersionComparisonTest extends TestCase
{
    use RefreshDatabase;

    public function test_compare_versions_detects_changes()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
        $assessment = Assessment::factory()->create(['tenant_id' => $tenant->id]);
        // Controls create karna zaroori hai
        Control::factory()->count(2)->create();

        $service = app(StatementOfApplicabilityService::class);
        $service->generateSoA($assessment);

        $v1 = $service->createVersion($assessment, $user->id, '1.0');
        $entry = $service->getSoAEntries($assessment)->first();
        $this->assertNotNull($entry, 'SoA entry should exist');
        $entry->applicable = false;
        $entry->save();
        $v2 = $service->createVersion($assessment, $user->id, '1.1');

        $differences = $service->compareVersions($v1, $v2);
        $this->assertNotEmpty($differences);
    }
}