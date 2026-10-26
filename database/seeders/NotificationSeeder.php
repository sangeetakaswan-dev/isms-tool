<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'owner@demo.com')->first();
        if (!$owner)
            return;

        $notifications = [
            [
                'type' => 'App\Notifications\RiskAssignedNotification',
                'data' => json_encode([
                    'title' => 'Risk Assigned: Unauthorized access to CRM database',
                    'message' => 'You have been assigned as the owner of a critical risk.',
                    'url' => '/risks/1',
                    'type' => 'risk_assigned',
                ]),
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\TreatmentDueNotification',
                'data' => json_encode([
                    'title' => 'Treatment Plan Due Soon',
                    'message' => 'Enable MFA for all employee email accounts is due in 3 days.',
                    'url' => '/risks/2',
                    'type' => 'treatment_due',
                ]),
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\RiskAssignedNotification',
                'data' => json_encode([
                    'title' => 'Risk Assigned: Phishing attack on employees',
                    'message' => 'You have been assigned as the owner of a high risk.',
                    'url' => '/risks/2',
                    'type' => 'risk_assigned',
                ]),
                'read_at' => now()->subDays(1),
            ],
        ];

        foreach ($notifications as $n) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => $n['type'],
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $owner->id,
                'data' => $n['data'],
                'read_at' => $n['read_at'],
                'created_at' => now()->subHours(rand(1, 48)),
                'updated_at' => now()->subHours(rand(1, 48)),
            ]);
        }

        $this->command->info('✓ Notifications created');
    }
}