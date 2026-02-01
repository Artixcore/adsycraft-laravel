<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BrandVoice>
 */
class BrandVoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => \App\Models\Workspace::factory(),
            'tone' => fake()->randomElement(['professional', 'casual', 'friendly', 'formal']),
            'style' => fake()->randomElement(['concise', 'detailed', 'conversational']),
            'keywords' => [fake()->word(), fake()->word()],
            'avoid_words' => [fake()->word()],
            'compliance_rules' => [],
            'language' => 'en',
        ];
    }
}
