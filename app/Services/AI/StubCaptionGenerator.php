<?php

namespace App\Services\AI;

use App\Models\BusinessAccount;

class StubCaptionGenerator
{
    /**
     * @return array<int, string> Array of caption strings (indexed by post number)
     */
    public function generate(BusinessAccount $business, int $count, string $date): array
    {
        $niche = $business->niche ?: 'your industry';
        $tone = $business->tone ?: 'professional';
        $lang = $business->language ?: 'en';

        $templates = [
            "Tip: In {$niche}, small steps lead to big results. What's your next move?",
            "Did you know? Trends in {$niche} are shifting. Stay ahead. 🚀",
            "This week in {$niche}: insights that matter. Share your take below.",
            "Quick win: One {$niche} habit that could change your game today.",
            "Question for you: What's the biggest challenge in {$niche} right now?",
            "Behind the scenes: How we approach {$niche} with a {$tone} tone.",
            "Resource roundup: Top 3 {$niche} tips we're using this month.",
            "Real talk: {$niche} isn't one-size-fits-all. Here's what works for us.",
            "Monday motivation: Your {$niche} journey is unique. Own it. 💪",
            "Friday focus: One {$niche} goal to crush before the week ends.",
        ];

        $captions = [];
        for ($i = 0; $i < $count; $i++) {
            $captions[] = $templates[$i % count($templates)];
        }

        return $captions;
    }
}
