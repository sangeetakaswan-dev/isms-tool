<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentTeam;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssessmentTeamSeeder extends Seeder
{
    public function run(): void
    {
        $assessments = Assessment::take(5)->get();
        $users = User::take(20)->get();

        foreach ($assessments as $assessment) {
            // Add lead assessor
            AssessmentTeam::create([
                'assessment_id' => $assessment->id,
                'user_id' => $users->random()->id,
                'role' => 'lead_assessor',
            ]);

            // Add assessors
            for ($i = 0; $i < 3; $i++) {
                AssessmentTeam::create([
                    'assessment_id' => $assessment->id,
                    'user_id' => $users->random()->id,
                    'role' => 'assessor',
                ]);
            }

            // Add reviewers
            for ($i = 0; $i < 2; $i++) {
                AssessmentTeam::create([
                    'assessment_id' => $assessment->id,
                    'user_id' => $users->random()->id,
                    'role' => 'reviewer',
                ]);
            }
        }
    }
}