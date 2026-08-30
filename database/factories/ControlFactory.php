<?php

namespace Database\Factories;

use App\Models\Control;
use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

class ControlFactory extends Factory
{
    protected $model = Control::class;

    public function definition(): array
    {
        return [
            'domain_id' => Domain::factory(),
            'control_id' => 'A.' . $this->faker->numberBetween(5, 8) . '.' . $this->faker->numberBetween(1, 34),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'implementation_guidance' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['organizational', 'people', 'physical', 'technological']),
            'control_type' => $this->faker->randomElement(['preventive', 'detective', 'corrective']),
            'is_active' => true,
        ];
    }
}