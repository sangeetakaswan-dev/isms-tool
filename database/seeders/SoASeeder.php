<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\SoAEntry;
use App\Models\SoAVersion;
use App\Models\Control;
use App\Models\User;
use Illuminate\Database\Seeder;

class SoASeeder extends Seeder
{
    public function run(): void
    {
        $assessment = Assessment::first();
        $owner = User::where('email', 'owner@demo.com')->first();

        if (!$assessment || !$owner)
            return;

        $controls = Control::all();
        $entriesData = [];

        foreach ($controls as $index => $control) {
            $applicable = $index < 105; // 105 applicable, 10 not applicable
            $isApproved = $index < 50;

            $entry = SoAEntry::create([
                'assessment_id' => $assessment->id,
                'control_id' => $control->id,
                'applicable' => $applicable,
                'justification' => $applicable ? 'Required based on risk assessment and business requirements.' : 'Not applicable to our current business model.',
                'implementation_status' => $applicable ? ($index % 2 == 0 ? 'fully_implemented' : 'partially_implemented') : 'not_applicable',
                'implementation_description' => 'Implemented via internal policies and technical controls.',
                'approved_by' => $isApproved ? $owner->id : null,
                'approved_at' => $isApproved ? now()->subDays(5) : null,
            ]);

            $entriesData[] = $entry->toArray();
        }

        // Create Version Snapshot
        SoAVersion::create([
            'assessment_id' => $assessment->id,
            'version' => '1.0',
            'data_snapshot' => json_encode($entriesData),
            'created_by' => $owner->id,
        ]);

        $this->command->info('✓ SoA Entries & Version 1.0 created');
    }
}