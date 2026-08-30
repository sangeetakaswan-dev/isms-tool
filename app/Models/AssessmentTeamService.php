<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentTeam;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssessmentTeamService
{
    public function addTeamMember(Assessment $assessment, int $userId, string $role): AssessmentTeam
    {
        // Check if user already exists in team
        if ($assessment->team()->where('user_id', $userId)->exists()) {
            throw ValidationException::withMessages([
                'user_id' => 'User is already a member of this assessment team.'
            ]);
        }

        // If adding as lead assessor, check if one already exists
        if ($role === AssessmentTeam::ROLE_LEAD_ASSESSOR) {
            if ($assessment->team()->where('role', AssessmentTeam::ROLE_LEAD_ASSESSOR)->exists()) {
                throw ValidationException::withMessages([
                    'role' => 'This assessment already has a lead assessor.'
                ]);
            }
        }

        return $assessment->team()->create([
            'user_id' => $userId,
            'role' => $role,
        ]);
    }

    public function removeTeamMember(Assessment $assessment, int $userId): bool
    {
        $member = $assessment->team()->where('user_id', $userId)->firstOrFail();
        
        // Prevent removing the only lead assessor if there are other members
        if ($member->isLeadAssessor()) {
            $leadAssessors = $assessment->team()->where('role', AssessmentTeam::ROLE_LEAD_ASSESSOR)->count();
            if ($leadAssessors <= 1 && $assessment->team()->count() > 1) {
                throw ValidationException::withMessages([
                    'user_id' => 'Cannot remove the only lead assessor. Assign another lead first.'
                ]);
            }
        }

        return $member->delete();
    }

    public function updateRole(Assessment $assessment, int $userId, string $newRole): AssessmentTeam
    {
        $member = $assessment->team()->where('user_id', $userId)->firstOrFail();
        
        // If changing to lead assessor, ensure no other lead exists
        if ($newRole === AssessmentTeam::ROLE_LEAD_ASSESSOR) {
            $existingLead = $assessment->team()
                ->where('role', AssessmentTeam::ROLE_LEAD_ASSESSOR)
                ->where('user_id', '!=', $userId)
                ->first();
            
            if ($existingLead) {
                throw ValidationException::withMessages([
                    'role' => 'This assessment already has a lead assessor.'
                ]);
            }
        }

        $member->update(['role' => $newRole]);
        return $member->fresh();
    }

    public function getTeamMembers(Assessment $assessment): Collection
    {
        return $assessment->team()->with('user')->get();
    }

    public function getTeamMembersByRole(Assessment $assessment, string $role): Collection
    {
        return $assessment->team()->with('user')->where('role', $role)->get();
    }

    public function bulkAddMembers(Assessment $assessment, array $members): array
    {
        $results = ['success' => [], 'failed' => []];

        DB::transaction(function () use ($assessment, $members, &$results) {
            foreach ($members as $member) {
                try {
                    $teamMember = $this->addTeamMember(
                        $assessment,
                        $member['user_id'],
                        $member['role']
                    );
                    $results['success'][] = $teamMember;
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'user_id' => $member['user_id'],
                        'error' => $e->getMessage()
                    ];
                }
            }
        });

        return $results;
    }
}