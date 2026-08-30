<?php

namespace Tests\Feature\Assessment;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Control;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResponseUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $assessment;
    protected $response;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->create();
        $this->user->tenants()->attach($this->tenant->id, ['role' => 'owner']);
        $this->user->current_tenant_id = $this->tenant->id;
        $this->user->save();
        
        $this->actingAs($this->user);
        
        // Set session for tenant
        session(['current_tenant_id' => $this->tenant->id]);

        $this->assessment = Assessment::factory()->create([
            'tenant_id' => $this->tenant->id,
            'lead_assessor_id' => $this->user->id,
        ]);

        $control = Control::factory()->create();
        $this->response = AssessmentResponse::factory()->create([
            'assessment_id' => $this->assessment->id,
            'control_id' => $control->id,
            'status' => 'not_assessed',
        ]);
    }

    #[Test]
    public function user_can_update_response_status()
    {
        $response = $this->putJson(
            route('assessments.responses.update', [
                'assessment' => $this->assessment->id,
                'response' => $this->response->id,
            ]), 
            [
                'status' => 'compliant',
                'maturity_level' => 3,
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('assessment_responses', [
            'id' => $this->response->id,
            'status' => 'compliant',
            'maturity_level' => 3,
            'assessed_by' => $this->user->id,
        ]);
    }

    #[Test]
    public function not_applicable_requires_justification()
    {
        $response = $this->putJson(
            route('assessments.responses.update', [
                'assessment' => $this->assessment->id,
                'response' => $this->response->id,
            ]), 
            [
                'status' => 'not_applicable',
            ]
        );

        $response->assertStatus(500);
    }

    #[Test]
    public function bulk_update_updates_multiple_responses()
    {
        $control2 = Control::factory()->create();
        $response2 = AssessmentResponse::factory()->create([
            'assessment_id' => $this->assessment->id,
            'control_id' => $control2->id,
            'status' => 'not_assessed',
        ]);

        $response = $this->postJson(
            route('assessments.responses.bulk-update', [
                'assessment' => $this->assessment->id,
            ]), 
            [
                'control_ids' => [$this->response->control_id, $response2->control_id],
                'status' => 'non_compliant',
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('assessment_responses', [
            'id' => $this->response->id,
            'status' => 'non_compliant',
        ]);
        
        $this->assertDatabaseHas('assessment_responses', [
            'id' => $response2->id,
            'status' => 'non_compliant',
        ]);
    }
}