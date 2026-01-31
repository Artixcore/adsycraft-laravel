<?php

namespace Database\Seeders;

use App\Models\BusinessAccount;
use App\Models\ContentPillar;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContentAutopilotSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $business = BusinessAccount::firstOrCreate(
            [
                'user_id' => $user->id,
                'name' => 'Demo Business',
            ],
            [
                'timezone' => 'America/New_York',
                'autopilot_enabled' => true,
                'meta_page_id' => null,
                'settings' => null,
            ]
        );

        $pillars = [
            ['name' => 'Product Updates', 'description' => 'New features and product news', 'weight' => 1],
            ['name' => 'Tips & How-to', 'description' => 'Educational content and tutorials', 'weight' => 2],
            ['name' => 'Behind the Scenes', 'description' => 'Company culture and team stories', 'weight' => 3],
            ['name' => 'User Stories', 'description' => 'Customer success and testimonials', 'weight' => 4],
            ['name' => 'Promotions', 'description' => 'Offers and special deals', 'weight' => 5],
        ];

        foreach ($pillars as $pillar) {
            ContentPillar::firstOrCreate(
                [
                    'business_account_id' => $business->id,
                    'name' => $pillar['name'],
                ],
                [
                    'description' => $pillar['description'],
                    'weight' => $pillar['weight'],
                ]
            );
        }
    }
}
