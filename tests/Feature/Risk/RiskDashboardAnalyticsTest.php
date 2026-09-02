<?php

namespace Tests\Feature\Risk;

use App\Models\RiskAssessment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\RiskDashboard;
use Tests\TestCase;


class RiskDashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_treatment_stats_computed()
    {
        RiskAssessment::factory()->count(3)->create(['treatment' => 'mitigate']);
        RiskAssessment::factory()->count(2)->create(['treatment' => 'accept']);

        Livewire::test(RiskDashboard::class)
            ->assertSet('treatmentStats', [
                'mitigate' => 3,
                'accept' => 2,
            ]);
    }

    public function test_trend_data_returns_months()
    {
        RiskAssessment::factory()->create(['created_at' => now()->subMonth()]);
        RiskAssessment::factory()->create(['created_at' => now()]);

        $component = Livewire::test(RiskDashboard::class);
        $trend = $component->get('trendData');
        $this->assertNotEmpty($trend);
    }
}