<?php

namespace Database\Factories;

use App\Models\BusinessAccount;
use App\Models\ContentPillar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContentPillar>
 */
class ContentPillarFactory extends Factory
{
    protected $model = ContentPillar::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_account_id' => BusinessAccount::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'weight' => 1,
        ];
    }
}
