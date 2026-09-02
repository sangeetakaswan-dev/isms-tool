<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\RiskAssessment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'contact_email' => 'test@tenant.com',
            'country' => 'India',
            'size' => '11-50',
        ]);

        $this->user = User::factory()->create([
            'current_tenant_id' => $this->tenant->id,
        ]);

        $this->tenant->users()->attach($this->user->id, ['role' => 'owner']);

        $this->actingAs($this->user);
    }

    public function test_can_view_risks_index()
    {
        $response = $this->get('/risks');
        $response->assertStatus(200);
    }

    public function test_risk_score_is_calculated_automatically()
    {
        // Create an asset for this tenant
        $asset = Asset::factory()->create(['tenant_id' => $this->tenant->id]);

        $risk = RiskAssessment::create([
            'tenant_id' => $this->tenant->id,
            'asset_id' => $asset->id,
            'threat' => 'Test Threat',
            'vulnerability' => 'Test Vulnerability',
            'likelihood' => 4,
            'impact' => 5,
            'risk_score' => 20,
            'risk_level' => 'critical',
            'status' => 'identified',
        ]);

        $this->assertEquals(20, $risk->risk_score);
        $this->assertEquals('critical', $risk->risk_level);
    }
}