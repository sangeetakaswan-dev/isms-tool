<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition()
    {
        return [
            'tenant_id' => 1,
            'name' => $this->faker->word() . ' ' . $this->faker->word(),
            'asset_type' => $this->faker->randomElement(['hardware', 'software', 'data', 'people', 'facility', 'service']),
            'owner' => $this->faker->name(),
            'department' => $this->faker->randomElement(['IT', 'HR', 'Finance', 'Operations']),
            'location' => $this->faker->city(),
            'confidentiality_rating' => $this->faker->numberBetween(1, 5),
            'integrity_rating' => $this->faker->numberBetween(1, 5),
            'availability_rating' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->sentence(),
        ];
    }
}
