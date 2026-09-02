<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a tenant and user, associate them
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

        // Attach user to tenant
        $this->tenant->users()->attach($this->user->id, ['role' => 'owner']);

        $this->actingAs($this->user);
    }

    public function test_can_view_assets_index()
    {
        $response = $this->get('/assets');
        $response->assertStatus(200);
    }

    public function test_can_create_asset()
    {
        $data = [
            'name' => 'Test Asset',
            'asset_type' => 'hardware',
            'confidentiality_rating' => 4,
            'integrity_rating' => 3,
            'availability_rating' => 5,
            // tenant_id is taken from auth user's current_tenant_id
        ];

        $response = $this->post('/assets', $data);
        $response->assertRedirect('/assets');
        $this->assertDatabaseHas('assets', [
            'name' => 'Test Asset',
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_can_update_asset()
    {
        $asset = Asset::factory()->create(['tenant_id' => $this->tenant->id]);

        $data = [
            'name' => 'Updated Asset Name',
            'asset_type' => 'software', // required
            'confidentiality_rating' => 3,
            'integrity_rating' => 4,
            'availability_rating' => 5,
            'description' => 'Updated description',
        ];

        $response = $this->put('/assets/' . $asset->id, $data);
        $response->assertRedirect('/assets');
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Updated Asset Name',
        ]);
    }

    public function test_can_delete_asset()
    {
        $asset = Asset::factory()->create(['tenant_id' => $this->tenant->id]);

        $response = $this->delete('/assets/' . $asset->id);
        $response->assertRedirect('/assets');
        $this->assertSoftDeleted('assets', ['id' => $asset->id]);
    }
}