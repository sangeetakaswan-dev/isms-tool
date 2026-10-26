<?php

namespace Database\Seeders;

use App\Models\Invitation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        $owner = User::where('email', 'owner@demo.com')->first();

        if (!$tenant || !$owner)
            return;

        Invitation::create([
            'tenant_id' => $tenant->id,
            'email' => 'newassessor@example.com',
            'token' => \Illuminate\Support\Str::random(64),
            'role' => 'assessor',
            'expires_at' => now()->addDays(7),
            'invited_by' => $owner->id,
        ]);

        Invitation::create([
            'tenant_id' => $tenant->id,
            'email' => 'auditor@example.com',
            'token' => \Illuminate\Support\Str::random(64),
            'role' => 'auditor',
            'expires_at' => now()->addDays(7),
            'invited_by' => $owner->id,
        ]);

        $this->command->info('✓ Invitations created');
    }
}