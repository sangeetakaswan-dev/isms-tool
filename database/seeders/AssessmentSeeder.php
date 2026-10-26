<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Control;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        $owner = User::where('email', 'owner@demo.com')->first();
        $assessor = User::where('email', 'assessor@demo.com')->first();

        if (!$tenant || !$owner)
            return;

        // 1. Create Assessment
        $assessment = Assessment::create([
            'tenant_id' => $tenant->id,
            'name' => 'Q4 2026 ISO 27001 Assessment',
            'description' => 'Annual full-scope assessment covering all 115 controls.',
            'scope' => 'full',
            'start_date' => now()->subDays(10)->toDateString(),
            'target_date' => now()->addDays(20)->toDateString(),
            'status' => 'in_progress',
            'progress_percentage' => 65,
            'lead_assessor_id' => $assessor->id,
            'team_members' => json_encode([$owner->id, $assessor->id]),
        ]);

        // 2. Create Responses (Randomized but realistic)
        $controls = Control::all();
        $statuses = ['compliant', 'partially_compliant', 'non_compliant', 'not_assessed', 'not_applicable'];

        foreach ($controls as $index => $control) {
            // 60% complete, 40% not assessed
            $isAssessed = $index < 70;
            $status = $isAssessed ? $statuses[array_rand(array_slice($statuses, 0, 3))] : 'not_assessed';

            AssessmentResponse::create([
                'assessment_id' => $assessment->id,
                'control_id' => $control->id,
                'status' => $status,
                'maturity_level' => $isAssessed ? rand(2, 5) : null,
                'evidence_notes' => $isAssessed ? 'Evidence reviewed and verified by the assessment team.' : null,
                'assessed_by' => $isAssessed ? $assessor->id : null,
                'assessed_at' => $isAssessed ? now()->subDays(rand(1, 9)) : null,
            ]);
        }

        $this->command->info('✓ Assessment & Responses created');
    }
}