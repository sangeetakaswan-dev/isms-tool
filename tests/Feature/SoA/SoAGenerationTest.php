<?php

namespace Tests\Feature\SoA;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Control;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Compliance\StatementOfApplicabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoAGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_soa_creates_entries_for_all_controls()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
        $assessment = Assessment::factory()->create(['tenant_id' => $tenant->id]);
        $controls = Control::factory()->count(5)->create();

        $service = app(StatementOfApplicabilityService::class);
        $service->generateSoA($assessment);

        $this->assertDatabaseCount('soa_entries', 5);
    }

    public function test_initial_applicability_based_on_assessment_response()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
        $assessment = Assessment::factory()->create(['tenant_id' => $tenant->id]);
        $control = Control::factory()->create();
        AssessmentResponse::factory()->create([
            'assessment_id' => $assessment->id,
            'control_id' => $control->id,
            'status' => 'not_applicable',
        ]);

        $service = app(StatementOfApplicabilityService::class);
        $service->generateSoA($assessment);

        $entry = $service->getSoAEntries($assessment)->first();
        $this->assertFalse($entry->applicable);
        $this->assertEquals('not_applicable', $entry->implementation_status);
    }
}