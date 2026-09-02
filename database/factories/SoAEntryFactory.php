<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Control;
use App\Models\SoAEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoAEntryFactory extends Factory
{
    protected $model = SoAEntry::class;

    public function definition()
    {
        return [
            'assessment_id' => Assessment::factory(),
            'control_id' => Control::factory(),
            'applicable' => true,
            'justification' => $this->faker->sentence(),
            'implementation_status' => 'not_implemented',
            'implementation_description' => null,
            'exclusion_reason' => null,
            'approved_by' => null,
            'approved_at' => null,
        ];
    }
}