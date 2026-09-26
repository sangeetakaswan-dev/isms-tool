<?php

namespace App\Repositories;

use App\Models\Document;
use App\Repositories\Contracts\DocumentRepositoryInterface;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function create(array $data): Document { return Document::create($data); }

    public function update(int $id, array $data): Document
    {
        $doc = Document::findOrFail($id);
        $doc->update($data);
        return $doc;
    }

    public function find(int $id): ?Document { return Document::find($id); }

    public function delete(int $id): void { Document::findOrFail($id)->delete(); }

    public function getByTenant(int $tenantId)
    {
        return Document::where('tenant_id', $tenantId)->with('owner')->get();
    }

    public function getDueForReview()
    {
        return Document::whereNotNull('review_date')
            ->where('review_date', '<=', now()->addDays(30))
            ->get();
    }
}