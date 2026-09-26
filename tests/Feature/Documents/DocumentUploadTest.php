<?php

namespace Tests\Feature\Documents;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_document()
    {
        Storage::fake('local');
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);

        $response = $this->actingAs($user)->post(route('documents.store'), [
            'title' => 'Test Policy',
            'document_type' => 'policy',
            'file' => UploadedFile::fake()->create('policy.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('documents.index'));
        $this->assertDatabaseHas('documents', ['title' => 'Test Policy']);
        $this->assertDatabaseHas('document_versions', ['version' => '1.0']);
    }
}