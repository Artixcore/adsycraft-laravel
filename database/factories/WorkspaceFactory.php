<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workspace>
 */
class WorkspaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company().' Workspace';

        return [
            'name' => $name,
            'slug' => fake()->unique()->slug(),
            'subscription_tier' => 'free',
            'subscription_status' => 'active',
            'subscription_expires_at' => null,
        ];
    }
}
