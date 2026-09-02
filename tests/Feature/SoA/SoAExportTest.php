<?php

namespace Tests\Feature\SoA;

use App\Models\Assessment;
use App\Models\Tenant;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoAExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_excel_export_downloads()
    {
        Excel::fake();
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
        $assessment = Assessment::factory()->create(['tenant_id' => $tenant->id]);

        $response = $this->actingAs($user)->get(route('soa.export.excel', $assessment->id));
        $response->assertOk();
        Excel::assertDownloaded('SoA_' . $assessment->id . '.xlsx');
    }
}