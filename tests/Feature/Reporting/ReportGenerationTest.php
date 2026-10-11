<?php

namespace Tests\Feature\Reporting;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportGenerationTest extends TestCase
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

        $this->user->tenants()->attach($this->tenant->id, [
            'role'       => 'owner',
            'is_default' => true,
        ]);

        $this->actingAs($this->user);
    }

    public function test_reports_index_loads(): void
    {
        $this->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Executive Summary');
    }

    public function test_can_view_executive_summary(): void
    {
        $this->get(route('reports.show', 'executive_summary'))
            ->assertOk()
            ->assertSee('Executive Summary');
    }

    public function test_can_export_csv(): void
    {
        $response = $this->get(route('reports.export', ['gap_analysis', 'csv']));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }

    public function test_unknown_report_type_returns_error(): void
    {
        $this->get(route('reports.show', 'does_not_exist'))
            ->assertStatus(500);
    }
}