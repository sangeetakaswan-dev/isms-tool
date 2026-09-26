<?php

namespace Tests\Feature\Documents;

use App\Models\Document;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Document\DocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_approve_document()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['current_tenant_id' => $tenant->id]);

        $document = Document::create([
            'tenant_id' => $tenant->id,
            'title' => 'Policy',
            'document_type' => 'policy',
            'file_path' => 'dummy',
            'owner_id' => $user->id,
            'status' => 'under_review',
        ]);

        $service = app(DocumentService::class);
        $service->approveDocument($document, $user->id);

        $this->assertEquals('approved', $document->fresh()->status);
        $this->assertNotNull($document->fresh()->approved_at);
    }
}