<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_tenant()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/tenants', [
            'name' => 'Test Organization',
            'size' => '11-50',
            'contact_email' => 'test@org.com',
            'country' => 'India',
        ]);

        $response->assertRedirect('/dashboard');
        
        // Check tenant exists (slug may have random suffix)
        $this->assertDatabaseHas('tenants', [
            'name' => 'Test Organization',
        ]);
        
        // Check user is attached as owner
        $tenant = Tenant::where('name', 'Test Organization')->first();
        $this->assertDatabaseHas('tenant_user', [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'role' => 'owner',
        ]);
    }

    public function test_tenant_requires_valid_data()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/tenants', [
            'name' => '',
            'size' => '',
            'contact_email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['name', 'size', 'contact_email']);
    }
}