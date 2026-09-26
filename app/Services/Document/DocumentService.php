<?php

namespace App\Services\Document;

use App\DTOs\DocumentData;
use App\Models\Control;
use App\Models\Document;
use App\Models\DocumentReview;
use App\Models\DocumentVersion;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository
    ) {}

    public function uploadDocument(DocumentData $data, UploadedFile $file): Document
    {
        return DB::transaction(function () use ($data, $file) {
            $path = $this->storeFile($data->tenantId, $data->documentType, $file);

            $document = $this->documentRepository->create([
                'tenant_id' => $data->tenantId,
                'title' => $data->title,
                'document_type' => $data->documentType,
                'category' => $data->category,
                'file_path' => $path,
                'owner_id' => $data->ownerId,
                'description' => $data->description,
                'tags' => $data->tags,
                'review_date' => $data->reviewDate,
                'status' => 'draft',
                'current_version' => '1.0',
            ]);

            // Initial version record
            DocumentVersion::create([
                'document_id' => $document->id,
                'version' => '1.0',
                'file_path' => $path,
                'changes' => 'Initial upload',
                'created_by' => Auth::id(),
            ]);

            return $document;
        });
    }

    public function createNewVersion(Document $document, UploadedFile $file, string $version, string $changes = null): DocumentVersion
    {
        $path = $this->storeFile($document->tenant_id, $document->document_type, $file);

        $versionRecord = DocumentVersion::create([
            'document_id' => $document->id,
            'version' => $version,
            'file_path' => $path,
            'changes' => $changes,
            'created_by' => Auth::id(),
        ]);

        $document->update([
            'current_version' => $version,
            'file_path' => $path,
        ]);

        return $versionRecord;
    }

    public function submitForReview(Document $document, array $reviewerIds): void
    {
        foreach ($reviewerIds as $reviewerId) {
            DocumentReview::create([
                'document_id' => $document->id,
                'reviewer_id' => $reviewerId,
                'status' => 'pending',
            ]);
        }
        $document->update(['status' => 'under_review']);
    }

    public function approveDocument(Document $document, int $approverId): void
    {
        $document->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);
    }

    public function rejectDocument(Document $document, string $comments): void
    {
        $document->update(['status' => 'draft']);
        $document->reviews()->latest()->first()?->update([
            'status' => 'rejected',
            'comments' => $comments,
            'reviewed_at' => now(),
        ]);
    }

    public function requestChanges(Document $document, string $comments): void
    {
        $document->update(['status' => 'draft']);
        $document->reviews()->latest()->first()?->update([
            'status' => 'changes_requested',
            'comments' => $comments,
            'reviewed_at' => now(),
        ]);
    }

    public function archiveDocument(Document $document): void
    {
        $document->update(['status' => 'archived']);
    }

    public function linkToControl(Document $document, Control $control): void
    {
        $document->controls()->syncWithoutDetaching([$control->id]);
    }

    public function getDocumentsDueForReview()
    {
        return $this->documentRepository->getDueForReview();
    }

    private function storeFile(int $tenantId, string $type, UploadedFile $file): string
    {
        $folder = "documents/{$tenantId}/{$type}s";
        return $file->store($folder, 'local');
    }
}