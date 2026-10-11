<?php

namespace Tests\Feature\Reporting;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::factory()->create();

        $this->user = User::factory()->create([
            'current_tenant_id' => $this->tenant->id,
        ]);

        // Pivot bhi attach kar do (belongsToTenant check ke liye)
        $this->user->tenants()->attach($this->tenant->id, [
            'role'       => 'owner',
            'is_default' => true,
        ]);

        $this->actingAs($this->user);
    }

    public function test_dashboard_loads_for_authenticated_user(): void
{
    $this->get(route('dashboard'))->assertOk();
}

    public function test_metrics_endpoint_returns_json(): void
    {
        $this->getJson(route('dashboard.metrics'))
            ->assertOk()
            ->assertJsonStructure([
                'compliance_score',
                'domain_compliance',
                'maturity_distribution',
                'risk_summary',
                'document_status',
                'counts',
            ]);
    }
}