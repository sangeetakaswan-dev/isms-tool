<?php

namespace App\Http\Controllers;

use App\DTOs\DocumentData;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Models\Control;
use App\Models\Document;
use App\Services\Document\DocumentService;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService
    ) {}

    public function index()
    {
        $documents = Document::with('owner')->where('tenant_id', Auth::user()->current_tenant_id)->get();
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(StoreDocumentRequest $request)
    {
        $data = new DocumentData(
            tenantId: Auth::user()->current_tenant_id,
            title: $request->title,
            documentType: $request->document_type,
            category: $request->category,
            ownerId: Auth::id(),
            description: $request->description,
            tags: $request->tags ? explode(',', $request->tags) : null,
            reviewDate: $request->review_date,
        );

        $this->documentService->uploadDocument($data, $request->file('file'));

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully');
    }

    public function show(Document $document)
    {
        $document->load(['versions', 'reviews.reviewer', 'controls']);
        $controls = Control::all();
        return view('documents.show', compact('document', 'controls'));
    }

    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document deleted');
    }

    public function linkControl(Document $document, Control $control)
    {
        $this->documentService->linkToControl($document, $control);
        return back()->with('success', 'Control linked');
    }
}