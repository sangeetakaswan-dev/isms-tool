<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $name = $this->faker->company();
        
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'industry' => $this->faker->randomElement([
                'Information Technology',
                'Healthcare',
                'Finance',
                'Manufacturing',
                'Retail',
            ]),
            'size' => $this->faker->randomElement(['1-10', '11-50', '51-200', '201-1000', '1000+']),
            'contact_email' => $this->faker->companyEmail(),
            'contact_phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => 'India',
            'pincode' => $this->faker->postcode(),
            'settings' => [
                'timezone' => 'Asia/Kolkata',
                'currency' => 'INR',
            ],
        ];
    }
}