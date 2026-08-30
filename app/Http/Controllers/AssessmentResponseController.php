<?php

namespace App\Http\Controllers;

use App\DTOs\AssessmentResponseData;
use App\Http\Requests\Assessment\BulkUpdateRequest;
use App\Http\Requests\Assessment\UpdateResponseRequest;
use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Domain;
use App\Services\Assessment\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentResponseController extends Controller
{
    public function __construct(
        private readonly ResponseService $responseService,
    ) {
    }

    public function showDomain(Assessment $assessment, Domain $domain): View
    {
        $responses = $this->responseService->getResponsesByDomain($assessment->id, $domain->id);
        $domainCompliance = $this->responseService->getDomainCompliance($domain, $assessment);
        $allDomains = Domain::orderBy('sort_order')->get();

        return view('assessments.conduct.domain', compact(
            'assessment',
            'domain',
            'responses',
            'domainCompliance',
            'allDomains'
        ));
    }

    public function update(UpdateResponseRequest $request, Assessment $assessment, AssessmentResponse $response): JsonResponse
    {
        $data = AssessmentResponseData::fromArray([
            'assessment_id' => $assessment->id,
            'control_id' => $response->control_id,
            'status' => $request->input('status'),
            'maturity_level' => $request->input('maturity_level'),
            'evidence_notes' => $request->input('evidence_notes'),
            'gap_description' => $request->input('gap_description'),
            'not_applicable_reason' => $request->input('not_applicable_reason'),
            'assigned_to' => $request->input('assigned_to'),
            'due_date' => $request->input('due_date'),
        ]);

        $updated = $this->responseService->updateResponse($response->id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Response updated successfully.',
            'response' => $updated->load(['control', 'assignedTo']),
        ]);
    }

    public function bulkUpdate(BulkUpdateRequest $request, Assessment $assessment): JsonResponse
    {
        $count = $this->responseService->bulkUpdate(
            $assessment->id,
            $request->input('control_ids'),
            $request->input('status')
        );

        return response()->json([
            'success' => true,
            'message' => "{$count} responses updated successfully.",
        ]);
    }

    public function bulkAssign(Request $request, Assessment $assessment): JsonResponse
    {
        $request->validate([
            'control_ids' => ['required', 'array', 'min:1'],
            'control_ids.*' => ['required', 'integer', 'exists:controls,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $count = $this->responseService->bulkAssign(
            $assessment->id,
            $request->input('control_ids'),
            $request->input('user_id')
        );

        return response()->json([
            'success' => true,
            'message' => "{$count} controls assigned successfully.",
        ]);
    }

    public function autoAssign(Request $request, Assessment $assessment): JsonResponse
    {
        $request->validate([
            'assignee_ids' => ['required', 'array', 'min:1'],
            'assignee_ids.*' => ['required', 'integer', 'exists:users,id'],
        ]);

        $result = $this->responseService->autoAssignControls(
            $assessment,
            $request->input('assignee_ids')
        );

        return response()->json([
            'success' => true,
            'message' => "{$result['assigned_count']} controls assigned to {$result['assignee_count']} team members.",
        ]);
    }

    public function uploadEvidence(Request $request, Assessment $assessment, AssessmentResponse $response): JsonResponse
    {
        $request->validate([
            'evidence_file' => [
                'required',
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif',
            ],
        ]);

        $result = $this->responseService->uploadEvidence(
            $response,
            $request->file('evidence_file')
        );

        return response()->json([
            'success' => true,
            'message' => 'Evidence uploaded successfully.',
            'evidence' => $result,
        ]);
    }

    public function index(Request $request, Assessment $assessment): View
    {
        $filters = [
            'status' => $request->input('status'),
            'domain_id' => $request->input('domain_id'),
            'assigned_to' => $request->input('assigned_to'),
            'search' => $request->input('search'),
            'sort_by' => $request->input('sort_by', 'control_id'),
            'sort_order' => $request->input('sort_order', 'asc'),
        ];

        $responses = $this->responseService->getPaginatedResponses(
            $assessment->id,
            25,
            $filters
        );

        $domains = Domain::orderBy('sort_order')->get();

        return view('assessments.conduct.index', compact(
            'assessment',
            'responses',
            'domains',
            'filters'
        ));
    }
}