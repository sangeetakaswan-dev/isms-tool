<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    protected $model = Domain::class;

    private static int $counter = 0;

    public function definition(): array
    {
        self::$counter++;
        
        $codes = [
            '4.1', '4.2', '4.3', '4.4',
            '5.1', '5.2', '5.3',
            '6.1', '6.2', '6.3',
            '7.1', '7.2', '7.3', '7.4', '7.5',
            '8.1', '8.2', '8.3',
            '9.1', '9.2', '9.3',
            '10.1', '10.2',
            'A.5', 'A.6', 'A.7', 'A.8',
        ];

        $code = $codes[(self::$counter - 1) % count($codes)];
        $sortOrder = self::$counter;

        return [
            'code' => $code,
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'clause_type' => str_starts_with($code, 'A.') ? 'annex_a' : 'main_clause',
            'sort_order' => $sortOrder,
        ];
    }
}