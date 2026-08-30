<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Services\AssessmentTeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssessmentTeamController extends Controller
{
    protected AssessmentTeamService $teamService;

    public function __construct(AssessmentTeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index(Assessment $assessment): JsonResponse
    {
        $this->authorize('viewTeam', $assessment);
        
        $team = $this->teamService->getTeamMembers($assessment);
        
        return response()->json([
            'data' => $team,
            'meta' => [
                'total' => $team->count(),
                'lead_assessors' => $team->where('role', 'lead_assessor')->count(),
                'assessors' => $team->where('role', 'assessor')->count(),
                'reviewers' => $team->where('role', 'reviewer')->count(),
            ]
        ]);
    }

    public function store(Request $request, Assessment $assessment): JsonResponse
    {
        $this->authorize('addTeamMember', $assessment);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => ['required', Rule::in(['lead_assessor', 'assessor', 'reviewer'])],
        ]);

        $teamMember = $this->teamService->addTeamMember(
            $assessment,
            $validated['user_id'],
            $validated['role']
        );

        return response()->json([
            'message' => 'Team member added successfully',
            'data' => $teamMember->load('user'),
        ], 201);
    }

    public function update(Request $request, Assessment $assessment, int $userId): JsonResponse
    {
        $this->authorize('updateTeamMember', $assessment);

        $validated = $request->validate([
            'role' => ['required', Rule::in(['lead_assessor', 'assessor', 'reviewer'])],
        ]);

        $teamMember = $this->teamService->updateRole(
            $assessment,
            $userId,
            $validated['role']
        );

        return response()->json([
            'message' => 'Team member role updated successfully',
            'data' => $teamMember->load('user'),
        ]);
    }

    public function destroy(Assessment $assessment, int $userId): JsonResponse
    {
        $this->authorize('removeTeamMember', $assessment);

        $this->teamService->removeTeamMember($assessment, $userId);

        return response()->json([
            'message' => 'Team member removed successfully',
        ]);
    }

    public function bulkStore(Request $request, Assessment $assessment): JsonResponse
    {
        $this->authorize('addTeamMember', $assessment);

        $validated = $request->validate([
            'members' => 'required|array|min:1',
            'members.*.user_id' => 'required|exists:users,id',
            'members.*.role' => ['required', Rule::in(['lead_assessor', 'assessor', 'reviewer'])],
        ]);

        $results = $this->teamService->bulkAddMembers($assessment, $validated['members']);

        return response()->json([
            'message' => 'Bulk add completed',
            'data' => $results,
        ]);
    }
}