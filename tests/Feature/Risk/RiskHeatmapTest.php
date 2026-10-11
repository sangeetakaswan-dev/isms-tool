<?php

namespace Tests\Feature\Risk;

use App\Models\Risk;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskHeatmapTest extends TestCase
{
    use RefreshDatabase;

    public function test_heatmap_data_endpoint_returns_grid(): void
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
        $user->tenants()->attach($tenant->id, ['role' => 'owner']);
        $this->actingAs($user);

        Risk::create([
            'tenant_id' => $tenant->id, 'title' => 'R1', 'category' => 'technical',
            'likelihood' => 4, 'impact' => 4, 'risk_score' => 16, 'risk_level' => 'high',
            'status' => 'identified',
        ]);
        Risk::create([
            'tenant_id' => $tenant->id, 'title' => 'R2', 'category' => 'technical',
            'likelihood' => 4, 'impact' => 4, 'risk_score' => 16, 'risk_level' => 'high',
            'status' => 'identified',
        ]);

        $response = $this->getJson(route('risks.heatmap.data'));

        $response->assertOk();
        $response->assertJsonPath('grid.4.4', 2);
    }
}