<?php

namespace Tests\Feature\Documents;

use App\Models\Document;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Document\DocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VersionControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_version_created()
    {
        Storage::fake('local');
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);

        $this->actingAs($user);

        $document = Document::create([
            'tenant_id' => $tenant->id,
            'title' => 'Policy',
            'document_type' => 'policy',
            'file_path' => 'dummy',
            'owner_id' => $user->id,
            'status' => 'draft',
        ]);

        $service = app(DocumentService::class);
        $service->createNewVersion(
            $document,
            UploadedFile::fake()->create('v2.pdf', 100, 'application/pdf'),
            '1.1',
            'Updated'
        );

        $this->assertDatabaseHas('document_versions', ['version' => '1.1', 'document_id' => $document->id]);
        $this->assertEquals('1.1', $document->fresh()->current_version);
    }
}