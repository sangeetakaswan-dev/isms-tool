<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\Document\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentReviewController extends Controller
{
    public function __construct(private DocumentService $documentService) {}

    public function submitForReview(Request $request, Document $document)
    {
        $request->validate(['reviewers' => 'required|array']);
        $this->documentService->submitForReview($document, $request->reviewers);
        return back()->with('success', 'Submitted for review');
    }

    public function approve(Document $document)
    {
        $this->documentService->approveDocument($document, Auth::id());
        return back()->with('success', 'Document approved');
    }

    public function reject(Request $request, Document $document)
    {
        $request->validate(['comments' => 'required|string']);
        $this->documentService->rejectDocument($document, $request->comments);
        return back()->with('success', 'Document rejected');
    }

    public function requestChanges(Request $request, Document $document)
    {
        $request->validate(['comments' => 'required|string']);
        $this->documentService->requestChanges($document, $request->comments);
        return back()->with('success', 'Changes requested');
    }
}