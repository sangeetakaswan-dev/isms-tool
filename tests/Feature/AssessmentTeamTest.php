<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentTeam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentTeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_team_member(): void
    {
        $assessment = Assessment::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson("/api/assessments/{$assessment->id}/team", [
            'user_id' => $user->id,
            'role' => 'assessor',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.user_id', $user->id);
    }

    public function test_cannot_add_duplicate_team_member(): void
    {
        $assessment = Assessment::factory()->create();
        $user = User::factory()->create();

        AssessmentTeam::create([
            'assessment_id' => $assessment->id,
            'user_id' => $user->id,
            'role' => 'assessor',
        ]);

        $response = $this->postJson("/api/assessments/{$assessment->id}/team", [
            'user_id' => $user->id,
            'role' => 'assessor',
        ]);

        $response->assertStatus(422);
    }

    public function test_can_remove_team_member(): void
    {
        $assessment = Assessment::factory()->create();
        $user = User::factory()->create();

        AssessmentTeam::create([
            'assessment_id' => $assessment->id,
            'user_id' => $user->id,
            'role' => 'assessor',
        ]);

        $response = $this->deleteJson("/api/assessments/{$assessment->id}/team/{$user->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('assessment_team', [
            'assessment_id' => $assessment->id,
            'user_id' => $user->id,
        ]);
    }
}