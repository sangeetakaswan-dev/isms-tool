<?php

namespace Tests\Feature\Risk;

use App\Models\Risk;
use App\Models\RiskTreatmentPlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskTreatmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->create(['current_tenant_id' => $this->tenant->id]);
        $this->user->tenants()->attach($this->tenant->id, ['role' => 'owner']);
        $this->actingAs($this->user);
    }

    public function test_treatment_plan_creation(): void
    {
        $risk = Risk::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Test Risk',
            'category' => 'technical',
            'likelihood' => 3,
            'impact' => 3,
            'risk_score' => 9,
            'risk_level' => 'medium',
            'status' => 'assessed',
        ]);

        $response = $this->post(route('risks.treatments.store', $risk), [
            'strategy' => 'mitigate',
            'action' => 'Deploy MFA for all users',
        ]);

        $response->assertRedirect(route('risks.show', $risk));
        $this->assertDatabaseHas('risk_treatment_plans', [
            'risk_id' => $risk->id,
            'strategy' => 'mitigate',
            'action' => 'Deploy MFA for all users',
            'status' => 'pending',
        ]);

        // Risk status should move to 'treated'
        $this->assertSame('treated', $risk->fresh()->status);
    }

    public function test_treatment_completion_moves_risk_to_monitored(): void
    {
        $risk = Risk::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Test Risk',
            'category' => 'technical',
            'likelihood' => 3,
            'impact' => 3,
            'risk_score' => 9,
            'risk_level' => 'medium',
            'status' => 'treated',
        ]);

        $plan = RiskTreatmentPlan::create([
            'risk_id' => $risk->id,
            'strategy' => 'mitigate',
            'action' => 'Test action',
            'status' => 'pending',
        ]);

        $response = $this->post(route('risks.treatments.complete', $plan), [
            'completion_notes' => 'Done',
        ]);

        $response->assertRedirect();
        $this->assertSame('completed', $plan->fresh()->status);
        $this->assertSame('monitored', $risk->fresh()->status);
    }
}