<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\RiskAssessment;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;


class RiskAssessmentFactory extends Factory
{
    protected $model = RiskAssessment::class;

    public function definition()
    {
        // Pehle tenant banayein
        $tenant = Tenant::factory()->create();

        // Us tenant ke saath asset create karein
        $asset = Asset::factory()->create(['tenant_id' => $tenant->id]);

        return [
            'tenant_id' => $tenant->id,  // ✅ ye line add karein
            'asset_id' => $asset->id,
            'threat' => $this->faker->sentence(3),
            'vulnerability' => $this->faker->sentence(3),
            'likelihood' => $this->faker->numberBetween(1, 5),
            'impact' => $this->faker->numberBetween(1, 5),
            'risk_score' => function (array $attributes) {
                return $attributes['likelihood'] * $attributes['impact'];
            },
            'risk_level' => function (array $attributes) {
                $score = $attributes['risk_score'];
                if ($score <= 4)
                    return 'low';
                if ($score <= 9)
                    return 'medium';
                if ($score <= 16)
                    return 'high';
                return 'critical';
            },
            'treatment' => $this->faker->randomElement(['accept', 'mitigate', 'transfer', 'avoid']),
            'treatment_description' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement(['identified', 'under_review', 'accepted', 'in_treatment', 'closed']),
            'review_date' => $this->faker->optional()->dateTimeBetween('+1 month', '+1 year'),
        ];
    }
}