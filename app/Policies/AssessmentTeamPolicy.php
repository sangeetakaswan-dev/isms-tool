<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;

class AssessmentTeamPolicy
{
    public function viewTeam(User $user, Assessment $assessment): bool
    {
        return $user->id === $assessment->created_by || 
               $user->hasPermissionTo('view assessment team');
    }

    public function addTeamMember(User $user, Assessment $assessment): bool
    {
        return $user->id === $assessment->created_by || 
               $user->hasPermissionTo('manage assessment team');
    }

    public function updateTeamMember(User $user, Assessment $assessment): bool
    {
        return $user->id === $assessment->created_by || 
               $user->hasPermissionTo('manage assessment team');
    }

    public function removeTeamMember(User $user, Assessment $assessment): bool
    {
        return $user->id === $assessment->created_by || 
               $user->hasPermissionTo('manage assessment team');
    }
}