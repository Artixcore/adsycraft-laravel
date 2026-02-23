<?php

namespace App\Services;

use App\Models\Post;

class ContentQualityService
{
    /**
     * Calculate a content quality score (0-100) for a post.
     * Rubric: Hook strength 25%, Caption length 25%, Hashtags 25%, Readability 25%.
     */
    public function calculate(Post $post): int
    {
        $caption = $post->caption ?? '';
        $hook = $post->hook ?? '';

        if (trim($caption) === '' && trim($hook) === '') {
            return 0;
        }

        $effectiveCaption = trim($caption) !== '' ? $caption : $hook;
        $lengthScore = $this->scoreCaptionLength($effectiveCaption);
        $hashtagScore = $this->scoreHashtagsFromCaption($effectiveCaption, $post->hashtags ?? []);
        $hookScore = $this->scoreHookStrength($post->hook ?? $effectiveCaption, $effectiveCaption);
        $readabilityScore = $this->scoreReadability($effectiveCaption);

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
     * Ideal: 5-10 hashtags. 0 or >15 penalize. Uses caption + hashtags array.
     */
    private function scoreHashtagsFromCaption(string $caption, array $hashtagsArray): float
    {
        preg_match_all('/#\w+/u', $caption, $matches);
        $fromCaption = count($matches[0] ?? []);
        $fromArray = is_array($hashtagsArray) ? count($hashtagsArray) : 0;
        $count = $fromCaption + $fromArray;

        if ($count === 0) {
            return 25;
        }
        if ($count > 15) {
            return max(25, 100 - ($count - 15) * 5);
        }
        if ($count >= 5 && $count <= 10) {
            return 100;
        }
        if ($count >= 3 && $count < 5) {
            return 75;
        }
        if ($count === 1 || $count === 2) {
            return 50 + $count * 10;
        }

        return max(50, 100 - ($count - 10) * 5);
    }

    /**
     * Hook strength: 0-25 no hook/generic, 26-50 weak, 51-75 clear/some intrigue, 76-100 strong/contrarian.
     */
    private function scoreHookStrength(string $hook, string $caption): float
    {
        $firstLine = trim($hook !== '' ? $hook : explode("\n", $caption)[0] ?? '');
        if ($firstLine === '') {
            return 25;
        }

        $firstLineLen = mb_strlen($firstLine);
        $score = 50;

        if ($firstLineLen >= 30 && $firstLineLen <= 150) {
            $score += 15;
        }
        if (preg_match('/\?$|^[A-Za-z].*[!.]$/', $firstLine)) {
            $score += 10;
        }
        if (preg_match('/\b(share|comment|tag|follow|click|learn|discover|try|check|get|why|how|secret|mistake)\b/i', $firstLine)) {
            $score += 15;
        }
        if (preg_match('/\b(never|always|stop|don\'t|myth|truth|actually)\b/i', $firstLine)) {
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
