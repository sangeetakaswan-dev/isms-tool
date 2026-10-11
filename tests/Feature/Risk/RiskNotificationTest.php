<?php

namespace Tests\Feature\Risk;

use App\Models\Risk;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\RiskAssignedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RiskNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_receives_notification_on_assignment(): void
    {
        Notification::fake();

        $tenant = Tenant::factory()->create();
        $owner = User::factory()->create(['current_tenant_id' => $tenant->id]);
        $owner->tenants()->attach($tenant->id, ['role' => 'assessor']);

        $creator = User::factory()->create(['current_tenant_id' => $tenant->id]);
        $creator->tenants()->attach($tenant->id, ['role' => 'owner']);
        $this->actingAs($creator);

        $this->post(route('risks.store'), [
            'title'      => 'Test Risk',
            'category'   => 'technical',
            'likelihood' => 3,
            'impact'     => 3,
            'owner_id'   => $owner->id,
        ]);

        Notification::assertSentTo($owner, RiskAssignedNotification::class);
    }
}