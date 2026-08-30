<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Control;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Demo Tenant
        $tenant = Tenant::create([
            'name' => 'Demo Organization',
            'slug' => 'demo-organization',
            'industry' => 'Information Technology',
            'size' => '51-200',
            'contact_email' => 'info@demo-organization.com',
            'contact_phone' => '+91 1234567890',
            'address' => '123 Tech Park',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
            'pincode' => '400001',
            'settings' => [
                'timezone' => 'Asia/Kolkata',
                'currency' => 'INR',
                'date_format' => 'd/m/Y',
            ],
        ]);

        // Create Users
        $owner = User::create([
            'name' => 'Demo Owner',
            'email' => 'owner@demo.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'current_tenant_id' => $tenant->id,
        ]);

        $assessor = User::create([
            'name' => 'Demo Assessor',
            'email' => 'assessor@demo.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'current_tenant_id' => $tenant->id,
        ]);

        $viewer = User::create([
            'name' => 'Demo Viewer',
            'email' => 'viewer@demo.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'current_tenant_id' => $tenant->id,
        ]);

        // Attach users to tenant
        $tenant->users()->attach($owner->id, ['role' => 'owner', 'is_default' => true]);
        $tenant->users()->attach($assessor->id, ['role' => 'assessor', 'is_default' => true]);
        $tenant->users()->attach($viewer->id, ['role' => 'viewer', 'is_default' => true]);

        // Create Sample Assessment
        $assessment = Assessment::create([
            'tenant_id' => $tenant->id,
            'name' => 'ISO 27001 Gap Assessment 2024',
            'description' => 'Initial gap assessment for ISO 27001:2022 certification',
            'scope' => 'All departments and information assets',
            'start_date' => now()->startOfMonth(),
            'target_date' => now()->addMonths(3),
            'status' => 'in_progress',
            'progress_percentage' => 0,
            'lead_assessor_id' => $assessor->id,
            'team_members' => [
                ['user_id' => $owner->id, 'role' => 'reviewer'],
                ['user_id' => $assessor->id, 'role' => 'assessor'],
                ['user_id' => $viewer->id, 'role' => 'observer'],
            ],
        ]);

        // Initialize some sample responses
        $controls = Control::active()->take(20)->get();
        
        foreach ($controls as $index => $control) {
            $statuses = ['compliant', 'non_compliant', 'partially_compliant', 'not_assessed'];
            $status = $statuses[$index % 4];
            
            AssessmentResponse::create([
                'assessment_id' => $assessment->id,
                'control_id' => $control->id,
                'status' => $status,
                'maturity_level' => $status === 'not_assessed' ? null : rand(0, 5),
                'evidence_notes' => $status !== 'not_assessed' ? 'Sample evidence for demonstration' : null,
                'gap_description' => $status === 'non_compliant' ? 'Control not implemented yet' : null,
                'assigned_to' => $assessor->id,
                'due_date' => now()->addWeeks(2),
                'assessed_by' => $status !== 'not_assessed' ? $assessor->id : null,
                'assessed_at' => $status !== 'not_assessed' ? now() : null,
            ]);
        }

        $this->command->info('Demo data created successfully!');
        $this->command->info('Owner: owner@demo.com / password');
        $this->command->info('Assessor: assessor@demo.com / password');
        $this->command->info('Viewer: viewer@demo.com / password');
    }
}