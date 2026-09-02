<?php

namespace Tests\Feature\Risk;

use App\Models\RiskAssessment;
use App\Services\Risk\RiskCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidualRiskCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_residual_risk_calculated_correctly()
    {
        $risk = RiskAssessment::factory()->create([
            'likelihood' => 4,
            'impact' => 4,
        ]);

        $controls = [
            ['status' => 'implemented'],
            ['status' => 'implemented'],
        ];

        $service = new RiskCalculationService();
        $residual = $service->calculateResidualRisk($risk, $controls);

        $this->assertEquals(3, $residual['residual_likelihood']); // 4 - floor(2*0.5) = 3
        $this->assertEquals(3, $residual['residual_impact']);
        $this->assertEquals(9, $residual['residual_score']);
        $this->assertEquals('medium', $residual['residual_level']);
    }
}