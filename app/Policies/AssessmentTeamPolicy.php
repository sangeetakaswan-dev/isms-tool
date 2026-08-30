<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;

class AssessmentTeamPolicy
{
    public function viewTeam(User $user, Assessment $assessment): bool
    {
        dd('viewTeam called');
        return true; // testing ke liye
    }

    public function addTeamMember(User $user, Assessment $assessment): bool
    {
        dd('addTeamMember called');
        return true;
    }

    public function updateTeamMember(User $user, Assessment $assessment): bool
    {
        dd('updateTeamMember called');
        return true;
    }

    public function removeTeamMember(User $user, Assessment $assessment): bool
    {
        dd('removeTeamMember called');
        return true;
    }
}