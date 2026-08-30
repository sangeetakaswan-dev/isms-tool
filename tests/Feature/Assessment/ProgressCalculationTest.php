<?php

namespace Tests\Feature\Assessment;

use App\DTOs\AssessmentResponseData;
use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Control;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Assessment\ResponseService;
use App\Services\Compliance\ComplianceCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProgressCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $assessment;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->create();
        $this->user->tenants()->attach($this->tenant->id, ['role' => 'owner']);
        $this->user->current_tenant_id = $this->tenant->id;
        $this->user->save();
        
        $this->actingAs($this->user);

        $this->assessment = Assessment::factory()->create([
            'tenant_id' => $this->tenant->id,
            'lead_assessor_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function progress_percentage_is_calculated_correctly()
    {
        // Create 4 controls
        $controls = Control::factory()->count(4)->create();
        
        // Create responses for all 4 controls
        foreach ($controls as $control) {
            AssessmentResponse::factory()->create([
                'assessment_id' => $this->assessment->id,
                'control_id' => $control->id,
                'status' => 'not_assessed',
            ]);
        }

        $responseService = app(\App\Services\Assessment\ResponseService::class);
        
        // Update 2 as assessed
        $responses = AssessmentResponse::where('assessment_id', $this->assessment->id)->take(2)->get();
        foreach ($responses as $response) {
            $responseService->updateResponse($response->id, \App\DTOs\AssessmentResponseData::fromArray([
                'assessment_id' => $this->assessment->id,
                'control_id' => $response->control_id,
                'status' => 'compliant',
                'maturity_level' => 3,
            ]));
        }

        $stats = $responseService->getProgressStats($this->assessment->id);
        
        $this->assertEquals(4, $stats['total']);
        $this->assertEquals(2, $stats['assessed']);
        $this->assertEquals(2, $stats['not_assessed']);
        $this->assertEquals(50, $stats['progress_percentage']);
    }

    #[Test]
    public function compliance_calculator_returns_accurate_score()
    {
        // Create 4 controls
        $controls = Control::factory()->count(4)->create();
        
        $statuses = ['compliant', 'compliant', 'non_compliant', 'partially_compliant'];
        
        foreach ($controls as $index => $control) {
            AssessmentResponse::factory()->create([
                'assessment_id' => $this->assessment->id,
                'control_id' => $control->id,
                'status' => $statuses[$index],
                'maturity_level' => 3,
            ]);
        }

        $complianceCalculator = app(\App\Services\Compliance\ComplianceCalculator::class);
        $score = $complianceCalculator->calculateOverallCompliance($this->assessment);
        
        // 2 compliant + 0.5 partial = 2.5 out of 4 = 62.5%
        $this->assertEquals(62.5, $score);
    }
}