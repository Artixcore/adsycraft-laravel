<?php

namespace Database\Factories;

use App\Models\BusinessAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BusinessAccount>
 */
class BusinessAccountFactory extends Factory
{
    protected $model = BusinessAccount::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company(),
            'timezone' => 'America/New_York',
            'autopilot_enabled' => false,
            'meta_page_id' => null,
            'settings' => null,
        ];
    }
}
