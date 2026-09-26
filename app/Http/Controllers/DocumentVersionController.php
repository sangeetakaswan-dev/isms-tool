<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\Document\DocumentService;
use Illuminate\Http\Request;

class DocumentVersionController extends Controller
{
    public function __construct(private DocumentService $documentService) {}

    public function store(Request $request, Document $document)
    {
        $request->validate([
            'version' => 'required|string',
            'changes' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:10240',
        ]);

        $this->documentService->createNewVersion(
            $document,
            $request->file('file'),
            $request->version,
            $request->changes
        );

        return back()->with('success', 'New version uploaded');
    }
}