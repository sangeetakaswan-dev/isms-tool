<?php

namespace Tests\Feature\SoA;

use App\Models\Assessment;
use App\Models\SoAEntry;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_approve_entry()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
        $assessment = Assessment::factory()->create(['tenant_id' => $tenant->id]);
        $entry = SoAEntry::factory()->create(['assessment_id' => $assessment->id]);

        $this->actingAs($user)
            ->post(route('soa.approve', [$assessment->id, $entry->id]))
            ->assertSessionHas('success');

        $entry->refresh();
        $this->assertNotNull($entry->approved_by);
        $this->assertNotNull($entry->approved_at);
    }
}