<?php

namespace Tests\Feature\Assessment;

use App\Models\Assessment;
use App\Models\Control;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AssessmentCreationTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->create();
        $this->user->tenants()->attach($this->tenant->id, ['role' => 'owner']);
        $this->user->current_tenant_id = $this->tenant->id;
        $this->user->save();

        $this->actingAs($this->user);

        session(['current_tenant_id' => $this->tenant->id]);
    }

    #[Test]
    public function user_can_create_assessment()
    {
        $response = $this->post(route('assessments.store'), [
            'name' => 'ISO 27001 Gap Assessment 2024',
            'description' => 'Initial gap assessment for ISO 27001 certification',
            'scope' => 'All departments and information systems',
            'start_date' => '2024-01-01',
            'target_date' => '2024-03-31',
            'lead_assessor_id' => $this->user->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assessments', [
            'name' => 'ISO 27001 Gap Assessment 2024',
            'tenant_id' => $this->tenant->id,
            'status' => 'draft',
        ]);
    }

    #[Test]
    public function assessment_requires_valid_data()
    {
        $response = $this->post(route('assessments.store'), [
            'name' => '',
            'start_date' => 'invalid-date',
        ]);

        $response->assertSessionHasErrors(['name', 'start_date']);
    }

    #[Test]
    public function assessment_responses_are_initialized()
    {
        // Seed controls first
        $domain = Domain::factory()->create([
            'code' => 'A.5',
            'name' => 'Organizational Controls',
        ]);

        Control::factory()->count(5)->create([
            'domain_id' => $domain->id,
            'is_active' => true,
        ]);

        $assessment = Assessment::factory()->create([
            'tenant_id' => $this->tenant->id,
            'lead_assessor_id' => $this->user->id,
        ]);

        $responseService = app(\App\Services\Assessment\ResponseService::class);
        $count = $responseService->initializeResponses($assessment);

        $this->assertGreaterThan(0, $count);
        $this->assertDatabaseHas('assessment_responses', [
            'assessment_id' => $assessment->id,
            'status' => 'not_assessed',
        ]);
    }
}