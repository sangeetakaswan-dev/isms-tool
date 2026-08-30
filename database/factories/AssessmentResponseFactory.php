<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Control;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentResponseFactory extends Factory
{
    protected $model = AssessmentResponse::class;

    public function definition(): array
    {
        return [
            'assessment_id' => Assessment::factory(),
            'control_id' => Control::factory(),
            'status' => 'not_assessed',
            'maturity_level' => null,
            'evidence_notes' => null,
            'gap_description' => null,
        ];
    }

    public function compliant(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'compliant',
            'maturity_level' => $this->faker->numberBetween(3, 5),
            'assessed_by' => User::factory(),
            'assessed_at' => now(),
        ]);
    }

    public function nonCompliant(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'non_compliant',
            'maturity_level' => $this->faker->numberBetween(0, 2),
            'gap_description' => $this->faker->paragraph(),
            'assessed_by' => User::factory(),
            'assessed_at' => now(),
        ]);
    }

    public function partiallyCompliant(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'partially_compliant',
            'maturity_level' => $this->faker->numberBetween(1, 3),
            'gap_description' => $this->faker->paragraph(),
            'assessed_by' => User::factory(),
            'assessed_at' => now(),
        ]);
    }
}