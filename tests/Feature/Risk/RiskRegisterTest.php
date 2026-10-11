<?php

namespace Tests\Feature\Risk;

use App\Models\Risk;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskRegisterTest extends TestCase
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

    public function test_can_create_risk_with_auto_score(): void
    {
        $response = $this->post(route('risks.store'), [
            'title'      => 'Unauthorized DB access',
            'category'   => 'technical',
            'likelihood' => 4,
            'impact'     => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('risks', [
            'title'      => 'Unauthorized DB access',
            'risk_score' => 20,
            'risk_level' => 'critical',
            'tenant_id'  => $this->tenant->id,
        ]);
    }

    public function test_can_update_risk(): void
    {
        $risk = Risk::create([
            'tenant_id'  => $this->tenant->id,
            'title'      => 'Old Title',
            'category'   => 'technical',
            'likelihood' => 2,
            'impact'     => 2,
            'risk_score' => 4,
            'risk_level' => 'low',
            'status'     => 'identified',
        ]);

        $response = $this->put(route('risks.update', $risk), [
            'title'      => 'Updated Title',
            'likelihood' => 5,
            'impact'     => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('risks', [
            'id'         => $risk->id,
            'title'      => 'Updated Title',
            'risk_score' => 25,
            'risk_level' => 'critical',
        ]);
    }

    public function test_can_delete_risk(): void
    {
        $risk = Risk::create([
            'tenant_id'  => $this->tenant->id,
            'title'      => 'Delete Me',
            'category'   => 'technical',
            'likelihood' => 1,
            'impact'     => 1,
            'risk_score' => 1,
            'risk_level' => 'low',
            'status'     => 'identified',
        ]);

        $response = $this->delete(route('risks.destroy', $risk));

        $response->assertRedirect(route('risks.index'));
        $this->assertDatabaseMissing('risks', ['id' => $risk->id]);
    }
}