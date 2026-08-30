<?php

namespace App\Http\Controllers;

use App\DTOs\AssessmentData;
use App\Http\Requests\Assessment\CreateAssessmentRequest;
use App\Http\Requests\Assessment\UpdateAssessmentRequest;
use App\Models\Assessment;
use App\Models\User;
use App\Services\Assessment\AssessmentService;
use App\Services\Assessment\ResponseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    public function __construct(
        private readonly AssessmentService $assessmentService,
        private readonly ResponseService $responseService,
    ) {
    }

    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->input('status'),
            'search' => $request->input('search'),
            'sort_by' => $request->input('sort_by', 'created_at'),
            'sort_order' => $request->input('sort_order', 'desc'),
        ];

        $assessments = $this->assessmentService->getPaginatedAssessments(12, $filters);

        $statusCounts = [
            'draft' => Assessment::where('status', 'draft')->count(),
            'in_progress' => Assessment::where('status', 'in_progress')->count(),
            'completed' => Assessment::where('status', 'completed')->count(),
            'archived' => Assessment::where('status', 'archived')->count(),
        ];

        return view('assessments.index', compact('assessments', 'filters', 'statusCounts'));
    }

    public function create(): View
    {
        $teamMembers = User::whereHas('tenants', function ($q) {
            $q->where('tenants.id', auth()->user()->current_tenant_id);
        })->get();

        return view('assessments.create', compact('teamMembers'));
    }

    public function store(CreateAssessmentRequest $request): RedirectResponse
    {
        $data = AssessmentData::fromArray([
            'tenant_id' => auth()->user()->current_tenant_id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'scope' => $request->input('scope'),
            'start_date' => $request->input('start_date'),
            'target_date' => $request->input('target_date'),
            'lead_assessor_id' => $request->input('lead_assessor_id', auth()->id()),
            'status' => 'draft',
        ]);

        $assessment = $this->assessmentService->createAssessment($data);

        $this->responseService->initializeResponses($assessment);

        if ($request->has('team_members')) {
            foreach ($request->input('team_members') as $userId => $role) {
                $this->assessmentService->assignTeamMember($assessment, $userId, $role);
            }
        }

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('success', 'Assessment created successfully.');
    }

    public function show(Assessment $assessment): View
    {
        $progressStats = $this->responseService->getProgressStats($assessment->id);
        $domainCompliance = $this->responseService->getAllDomainCompliance($assessment);
        $teamMembers = $this->assessmentService->getTeamMembers($assessment);

        return view('assessments.show', compact(
            'assessment',
            'progressStats',
            'domainCompliance',
            'teamMembers'
        ));
    }

    public function edit(Assessment $assessment): View
    {
        $teamMembers = User::whereHas('tenants', function ($q) {
            $q->where('tenants.id', auth()->user()->current_tenant_id);
        })->get();

        $currentTeam = $this->assessmentService->getTeamMembers($assessment);

        return view('assessments.edit', compact('assessment', 'teamMembers', 'currentTeam'));
    }

    public function update(UpdateAssessmentRequest $request, Assessment $assessment): RedirectResponse
    {
        $data = AssessmentData::fromArray([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'scope' => $request->input('scope'),
            'start_date' => $request->input('start_date'),
            'target_date' => $request->input('target_date'),
            'lead_assessor_id' => $request->input('lead_assessor_id'),
        ]);

        $this->assessmentService->updateAssessment($assessment->id, $data);

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('success', 'Assessment updated successfully.');
    }

    public function start(Assessment $assessment): RedirectResponse
    {
        $this->assessmentService->startAssessment($assessment);

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('success', 'Assessment started.');
    }

    public function complete(Assessment $assessment): RedirectResponse
    {
        try {
            $this->assessmentService->completeAssessment($assessment);
            return redirect()
                ->route('assessments.show', $assessment)
                ->with('success', 'Assessment completed successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('assessments.show', $assessment)
                ->with('error', $e->getMessage());
        }
    }

    public function archive(Assessment $assessment): RedirectResponse
    {
        $this->assessmentService->archiveAssessment($assessment);

        return redirect()
            ->route('assessments.index')
            ->with('success', 'Assessment archived successfully.');
    }

    public function destroy(Assessment $assessment): RedirectResponse
    {
        $this->assessmentService->deleteAssessment($assessment->id);

        return redirect()
            ->route('assessments.index')
            ->with('success', 'Assessment deleted successfully.');
    }
}