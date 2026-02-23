<?php

namespace App\Services\AI\Memory;

use App\Models\BusinessAccount;
use App\Models\Post;

class SharedMemoryService
{
    /**
     * Get Business Profile Memory (static) for a business account.
     *
     * @return array<string, mixed>
     */
    public function getBusinessProfileMemory(BusinessAccount $business): array
    {
        $settings = $business->settings ?? [];

        return [
            'name' => $business->name,
            'niche' => $business->niche,
            'website_url' => $business->website_url,
            'tone' => $business->tone,
            'language' => $business->language ?? 'en',
            'timezone' => $business->timezone ?? 'UTC',
            'target_locations' => $settings['target_locations'] ?? [],
            'goals' => $settings['goals'] ?? [],
            'budget_range_min' => $settings['budget_range_min'] ?? null,
            'budget_range_max' => $settings['budget_range_max'] ?? null,
            'customer_persona_hints' => $settings['customer_persona_hints'] ?? null,
        ];
    }

    /**
     * Get Brand Voice Memory (semi-static) for a business account.
     * Uses workspace brand voices; prefers one linked to business meta assets.
     *
     * @return array<string, mixed>|null
     */
    public function getBrandVoiceMemory(BusinessAccount $business): ?array
    {
        $workspace = $business->workspace;
        if (! $workspace) {
            return null;
        }

        $metaAssetIds = $business->metaAssets()->pluck('id')->toArray();
        $voice = $workspace->brandVoices()
            ->when(! empty($metaAssetIds), fn ($q) => $q->whereIn('meta_asset_id', $metaAssetIds))
            ->first();

        if (! $voice) {
            $voice = $workspace->brandVoices()->first();
        }

        if (! $voice) {
            return null;
        }

        return [
            'tone' => $voice->tone,
            'style' => $voice->style,
            'keywords' => $voice->keywords ?? [],
            'avoid_words' => $voice->avoid_words ?? [],
            'compliance_rules' => $voice->compliance_rules ?? [],
            'language' => $voice->language ?? $business->language,
        ];
    }

    /**
     * Get Market Intelligence Memory (updates weekly).
     *
     * @return array<string, mixed>|null
     */
    public function getMarketIntelligenceMemory(BusinessAccount $business): ?array
    {
        $intel = $business->marketIntelligence;

        if (! $intel) {
            return null;
        }

        return [
            'research_output' => $intel->research_output,
            'trend_output' => $intel->trend_output,
            'competitor_ad_data' => $intel->competitor_ad_data,
            'refreshed_at' => $intel->refreshed_at?->toIso8601String(),
        ];
    }

    /**
     * Get Content Performance Memory (updates daily) - recent post metrics.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getContentPerformanceMemory(BusinessAccount $business, int $days = 7): array
    {
        $posts = $business->posts()
            ->where('published_at', '>=', now()->subDays($days))
            ->whereNotNull('published_at')
            ->with('postMetric')
            ->latest('published_at')
            ->limit(50)
            ->get();

        return $posts->map(function (Post $post) {
            $metric = $post->postMetric;

            $engagement = $metric
                ? (int) (($metric->likes ?? 0) + ($metric->comments ?? 0) + ($metric->shares ?? 0) + ($metric->saves ?? 0))
                : 0;

            return [
                'post_id' => $post->id,
                'caption_preview' => mb_substr($post->caption ?? '', 0, 100),
                'published_at' => $post->published_at?->toIso8601String(),
                'reach' => $metric?->reach,
                'impressions' => $metric?->impressions,
                'engagement' => $engagement,
                'engagement_rate' => $metric?->engagement_rate,
            ];
        })->values()->toArray();
    }

    /**
     * Get full shared memory context for agent prompts.
     *
     * @return array<string, mixed>
     */
    public function getFullContext(BusinessAccount $business): array
    {
        $bpm = $this->getBusinessProfileMemory($business);
        $bvm = $this->getBrandVoiceMemory($business);
        $mim = $this->getMarketIntelligenceMemory($business);
        $cpm = $this->getContentPerformanceMemory($business);

        $brandVoiceSummary = $bvm
            ? sprintf(
                'Tone: %s, Style: %s, Keywords: %s, Avoid: %s',
                $bvm['tone'] ?? 'N/A',
                $bvm['style'] ?? 'N/A',
                implode(', ', $bvm['keywords'] ?? []),
                implode(', ', $bvm['avoid_words'] ?? [])
            )
            : 'Not configured';

        return [
            'business_profile' => $bpm,
            'brand_voice' => $bvm,
            'brand_voice_summary' => $brandVoiceSummary,
            'market_intelligence' => $mim,
            'content_performance' => $cpm,
            'competitor_urls' => $business->competitorUrls()
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($c) => [
                    'url' => $c->url,
                    'page_name' => $c->page_name,
                    'meta_page_id' => $c->meta_page_id,
                ])
                ->toArray(),
        ];
    }
}
