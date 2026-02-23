<?php

namespace App\Services;

use App\Models\Post;

class ContentQualityService
{
    /**
     * Calculate a content quality score (0-100) for a post.
     */
    public function calculate(Post $post): int
    {
        $caption = $post->caption ?? '';

        if (trim($caption) === '') {
            return 0;
        }

        $lengthScore = $this->scoreCaptionLength($caption);
        $hashtagScore = $this->scoreHashtags($caption);
        $hookScore = $this->scoreHookStrength($caption);
        $readabilityScore = $this->scoreReadability($caption);

        $total = ($lengthScore * 0.25) + ($hashtagScore * 0.25) + ($hookScore * 0.25) + ($readabilityScore * 0.25);

        return (int) min(100, max(0, round($total)));
    }

    /**
     * Optimal: 50-300 chars. Too short or too long penalize.
     */
    private function scoreCaptionLength(string $caption): float
    {
        $len = mb_strlen($caption);
        if ($len < 20) {
            return 20;
        }
        if ($len >= 50 && $len <= 300) {
            return 100;
        }
        if ($len > 300 && $len <= 500) {
            return 80;
        }
        if ($len > 500) {
            return max(40, 100 - ($len - 500) / 10);
        }

        return 50 + ($len - 20) / 30 * 50;
    }

    /**
     * Ideal: 3-10 hashtags. None or too many penalize.
     */
    private function scoreHashtags(string $caption): float
    {
        preg_match_all('/#\w+/u', $caption, $matches);
        $count = count($matches[0] ?? []);

        if ($count === 0) {
            return 40;
        }
        if ($count >= 3 && $count <= 10) {
            return 100;
        }
        if ($count === 1 || $count === 2) {
            return 60 + $count * 15;
        }

        return max(50, 100 - ($count - 10) * 5);
    }

    /**
     * Strong hook: first line 50-150 chars, question or CTA.
     */
    private function scoreHookStrength(string $caption): float
    {
        $firstLine = trim(explode("\n", $caption)[0] ?? '');
        $firstLineLen = mb_strlen($firstLine);

        $score = 50;
        if ($firstLineLen >= 30 && $firstLineLen <= 150) {
            $score += 25;
        }
        if (preg_match('/\?$|^[A-Za-z].*[!.]$/', $firstLine)) {
            $score += 15;
        }
        if (preg_match('/\b(share|comment|tag|follow|click|learn|discover|try|check|get)\b/i', $firstLine)) {
            $score += 10;
        }

        return min(100, $score);
    }

    /**
     * Readability: reasonable sentence length (avg 10-20 words), word count.
     */
    private function scoreReadability(string $caption): float
    {
        $sentences = preg_split('/[.!?]+/', $caption, -1, PREG_SPLIT_NO_EMPTY);
        $sentences = array_filter(array_map('trim', $sentences));

        if (empty($sentences)) {
            return 50;
        }

        $totalWords = 0;
        foreach ($sentences as $s) {
            $totalWords += str_word_count($s);
        }
        $avgWords = $totalWords / max(1, count($sentences));

        $score = 70;
        if ($avgWords >= 10 && $avgWords <= 20) {
            $score += 30;
        } elseif ($avgWords >= 5 && $avgWords <= 30) {
            $score += 15;
        }

        return min(100, $score);
    }
}
