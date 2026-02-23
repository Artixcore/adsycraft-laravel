<?php

namespace App\Services;

/**
 * Scores how well a trend fits a brand (0-100).
 * Rubric: Brand alignment 40%, Audience relevance 30%, Shelf-life 15%, Execution feasibility 15%.
 */
class TrendFitScorer
{
    /**
     * Score a trend's fit for a business.
     *
     * @param  array{name?: string, shelf_life?: string, brand_fit_score?: int}  $trend
     * @param  array{keywords?: array, avoid_words?: array}  $brandVoice
     * @param  array{target_segments?: array}  $marketMap
     * @return int 0-100
     */
    public function score(array $trend, array $brandVoice = [], array $marketMap = []): int
    {
        $brandAlignment = $this->scoreBrandAlignment($trend, $brandVoice);
        $audienceRelevance = $this->scoreAudienceRelevance($trend, $marketMap);
        $shelfLife = $this->scoreShelfLife($trend['shelf_life'] ?? '');
        $executionFeasibility = $this->scoreExecutionFeasibility($trend);

        $total = ($brandAlignment * 0.40) + ($audienceRelevance * 0.30) + ($shelfLife * 0.15) + ($executionFeasibility * 0.15);

        return (int) min(100, max(0, round($total)));
    }

    /**
     * Brand alignment: 0-25 conflicts, 26-50 neutral, 51-75 aligns, 76-100 strongly reinforces.
     */
    private function scoreBrandAlignment(array $trend, array $brandVoice): float
    {
        $agentScore = (int) ($trend['brand_fit_score'] ?? 5);
        $avoidWords = $brandVoice['avoid_words'] ?? [];
        $keywords = $brandVoice['keywords'] ?? [];
        $trendName = strtolower($trend['name'] ?? $trend['why_rising'] ?? '');

        foreach ($avoidWords as $word) {
            if (is_string($word) && str_contains($trendName, strtolower($word))) {
                return 25;
            }
        }

        $keywordMatches = 0;
        foreach ($keywords as $kw) {
            if (is_string($kw) && str_contains($trendName, strtolower($kw))) {
                $keywordMatches++;
            }
        }

        $base = 50 + ($agentScore - 5) * 5;
        if ($keywordMatches > 0) {
            $base = min(100, $base + 20);
        }

        return min(100, max(25, $base));
    }

    /**
     * Audience relevance: 0-25 wrong, 26-50 partial, 51-75 good fit, 76-100 perfect.
     */
    private function scoreAudienceRelevance(array $trend, array $marketMap): float
    {
        $agentScore = (int) ($trend['brand_fit_score'] ?? 5);
        $segments = $marketMap['target_segments'] ?? [];

        if (empty($segments)) {
            return 50 + ($agentScore - 5) * 5;
        }

        return min(100, max(25, 50 + ($agentScore - 5) * 6));
    }

    /**
     * Shelf-life: 0-25 expired, 26-50 <1 week, 51-75 1-4 weeks, 76-100 1+ month.
     */
    private function scoreShelfLife(string $shelfLife): float
    {
        $s = strtolower($shelfLife);
        if (str_contains($s, 'expired') || str_contains($s, 'over')) {
            return 25;
        }
        if (preg_match('/\d+\s*day/', $s) && ! preg_match('/\d+\s*(week|month)/', $s)) {
            $days = (int) preg_replace('/\D/', '', $s);
            if ($days < 7) {
                return 50;
            }
            if ($days < 28) {
                return 75;
            }
        }
        if (preg_match('/\d+\s*week/', $s) || preg_match('/month/', $s) || preg_match('/quarter/', $s)) {
            return 100;
        }

        return 75;
    }

    /**
     * Execution feasibility: 0-25 not doable, 26-50 hard, 51-75 moderate, 76-100 easy.
     */
    private function scoreExecutionFeasibility(array $trend): float
    {
        $name = strtolower($trend['name'] ?? '');
        $hardPatterns = ['live stream', 'video', 'ugc', 'influencer', 'collab'];
        foreach ($hardPatterns as $p) {
            if (str_contains($name, $p)) {
                return 60;
            }
        }

        return 85;
    }
}
