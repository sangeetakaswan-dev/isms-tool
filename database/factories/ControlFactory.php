<?php

namespace Database\Factories;

use App\Models\Control;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ControlFactory extends Factory
{
    protected $model = Control::class;

    public function definition()
    {
        return [
            'domain_id' => \App\Models\Domain::factory(),
            'control_id' => 'TEST-' . Str::upper(Str::random(8)), // unique har call par
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'implementation_guidance' => $this->faker->optional()->paragraph(),
            'category' => $this->faker->randomElement(['organizational', 'people', 'physical', 'technological']),
            'control_type' => $this->faker->optional()->randomElement(['preventive', 'detective', 'corrective']),
            'is_active' => true,
        ];
    }
}