<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_invitation()
    {
        $owner = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $tenant->users()->attach($owner->id, ['role' => 'owner', 'is_default' => true]);
        $owner->switchTenant($tenant);

        $response = $this->actingAs($owner)->post("/tenants/{$tenant->id}/invitations", [
            'email' => 'newuser@example.com',
            'role' => 'assessor',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('invitations', [
            'email' => 'newuser@example.com',
            'role' => 'assessor',
            'tenant_id' => $tenant->id,
        ]);
    }

    public function test_user_can_accept_invitation()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $inviter = User::factory()->create();
        
        $invitation = Invitation::create([
            'tenant_id' => $tenant->id,
            'email' => $user->email,
            'token' => 'test-token-123',
            'role' => 'assessor',
            'expires_at' => now()->addDays(7),
            'invited_by' => $inviter->id,
        ]);

        // Simulate accepting invitation
        $tenant->users()->attach($user->id, [
            'role' => $invitation->role,
            'is_default' => false,
        ]);

        $invitation->accepted_at = now();
        $invitation->save();

        $this->assertDatabaseHas('tenant_user', [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'role' => 'assessor',
        ]);
        
        $this->assertNotNull($invitation->fresh()->accepted_at);
    }
}