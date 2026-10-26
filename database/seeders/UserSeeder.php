<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Demo Tenant ────────────────────────────────
        // Actual schema: name, slug, contact_email (required), size (enum), etc.
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'demo-organization'],
            [
                'name' => 'Demo Organization',
                'contact_email' => 'admin@demo.com',
                'contact_phone' => '+91 98765 43210',
                'industry' => 'Technology',
                'size' => '11-50',          // valid enum value
                'address' => '123 Tech Park, MG Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'country' => 'India',
                'pincode' => '560001',
            ]
        );

        $this->command->info("✓ Tenant: {$tenant->name} (ID: {$tenant->id})");

        // ─── 2. Demo Users ────────────────────────────────
        $demoUsers = [
            ['name' => 'Demo Owner', 'email' => 'owner@demo.com', 'role' => 'owner'],
            ['name' => 'Demo Assessor', 'email' => 'assessor@demo.com', 'role' => 'assessor'],
            ['name' => 'Demo Viewer', 'email' => 'viewer@demo.com', 'role' => 'viewer'],
        ];

        foreach ($demoUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'current_tenant_id' => $tenant->id,
                    'is_active' => true,
                ]
            );

            // Agar user pehle se hai but tenant set nahi hai
            if (!$user->current_tenant_id) {
                $user->update(['current_tenant_id' => $tenant->id]);
            }

            // Pivot attach with role
            if (!$user->tenants()->where('tenants.id', $tenant->id)->exists()) {
                $user->tenants()->attach($tenant->id, ['role' => $userData['role']]);
            }

            $this->command->info("✓ User: {$user->email} ({$userData['role']})");
        }

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('  DEMO CREDENTIALS');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('  owner@demo.com    / password');
        $this->command->info('  assessor@demo.com / password');
        $this->command->info('  viewer@demo.com   / password');
        $this->command->info('═══════════════════════════════════════');
    }
}