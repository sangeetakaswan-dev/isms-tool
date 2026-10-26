<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
                // Base Data
            DomainSeeder::class,
            ControlSeeder::class,

                // Users & Orgs
            UserSeeder::class,

                // Assets & Risks
            AssetSeeder::class,
            RiskSeeder::class,
            TreatmentPlanSeeder::class,

                // Assessments & SoA
            AssessmentSeeder::class,
            SoASeeder::class,

                // Documents
            DocumentSeeder::class,

                // Notifications & Invitations
            NotificationSeeder::class,
            InvitationSeeder::class,
        ]);
    }
}