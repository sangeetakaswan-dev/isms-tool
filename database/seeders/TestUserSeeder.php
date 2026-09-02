<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Demo Owner',
                'email' => 'owner@demo.com',
                'password' => Hash::make('password'),
                'current_tenant_id' => 1,
                'role' => 'owner',
            ],
            [
                'name' => 'Demo Assessor',
                'email' => 'assessor@demo.com',
                'password' => Hash::make('password'),
                'current_tenant_id' => 1,
                'role' => 'assessor',
            ],
            [
                'name' => 'Demo Viewer',
                'email' => 'viewer@demo.com',
                'password' => Hash::make('password'),
                'current_tenant_id' => 1,
                'role' => 'viewer',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            $user = User::create($userData);
            $user->tenants()->attach(1, ['role' => $role]);
            // Spatie permission ke liye
            $user->assignRole($role);
        }
    }
}