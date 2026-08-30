<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'scope' => $this->faker->paragraph(),
            'start_date' => $this->faker->date(),
            'target_date' => $this->faker->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
            'status' => 'draft',
            'progress_percentage' => 0,
            'lead_assessor_id' => User::factory(),
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_date' => now(),
            'progress_percentage' => 100,
        ]);
    }
}
