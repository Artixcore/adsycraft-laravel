<?php

namespace App\Services\AI\Agents;

use App\Models\BusinessAccount;
use App\Services\AI\AIManager;
use App\Services\AI\Memory\SharedMemoryService;
use Illuminate\Support\Facades\Log;

class OrchestratorAgent
{
    public function __construct(
        private readonly AIManager $aiManager,
        private readonly SharedMemoryService $memory,
        private readonly ResearchAgent $researchAgent,
        private readonly TrendAgent $trendAgent,
        private readonly PositioningAgent $positioningAgent,
        private readonly AdAgent $adAgent
    ) {}

    /**
     * Run full Growth Blueprint generation: Research -> Trend -> Positioning -> Ad -> Merge.
     *
     * @param  array{ad_library_data?: array, web_search_results?: string}  $extraInputs
     * @return array<string, mixed> Growth Blueprint payload
     */
    public function run(BusinessAccount $business, array $extraInputs = []): array
    {
        $adLibraryData = $extraInputs['ad_library_data'] ?? [];
        $webSearchResults = $extraInputs['web_search_results'] ?? '';

        $researchOutput = $this->researchAgent->run($business, [
            'ad_library_data' => $adLibraryData,
            'web_search_results' => $webSearchResults,
        ]);

        $trendOutput = $this->trendAgent->run($business, [
            'web_search_results' => $webSearchResults,
        ]);

        $positioningOutput = $this->positioningAgent->run($business, [
            'research_output' => $researchOutput,
            'trend_output' => $trendOutput,
        ]);

        $adOutput = $this->adAgent->run($business, [
            'research_output' => $researchOutput,
            'trend_output' => $trendOutput,
            'positioning_output' => $positioningOutput,
            'ad_library_data' => $adLibraryData,
        ]);

        return $this->mergeAndFinalize($business, $researchOutput, $trendOutput, $positioningOutput, $adOutput, $extraInputs);
    }

    /**
     * Merge agent outputs into final Growth Blueprint. Uses Orchestrator prompt for content calendar and disruptor moves.
     *
     * @return array<string, mixed>
     */
    protected function mergeAndFinalize(
        BusinessAccount $business,
        array $researchOutput,
        array $trendOutput,
        array $positioningOutput,
        array $adOutput,
        array $extraInputs
    ): array {
        $context = $this->memory->getFullContext($business);

        $prompt = $this->loadPrompt();
        $prompt = str_replace(
            [
                '{{business_name}}',
                '{{niche}}',
                '{{goal}}',
                '{{brand_voice_summary}}',
                '{{research_output}}',
                '{{trend_output}}',
                '{{positioning_output}}',
                '{{ad_output}}',
            ],
            [
                $business->name ?? 'Business',
                $business->niche ?? 'general',
                'Generate full Growth Blueprint',
                $context['brand_voice_summary'],
                json_encode($researchOutput, JSON_PRETTY_PRINT),
                json_encode($trendOutput, JSON_PRETTY_PRINT),
                json_encode($positioningOutput, JSON_PRETTY_PRINT),
                json_encode($adOutput, JSON_PRETTY_PRINT),
            ],
            $prompt
        );

        $result = $this->aiManager->execute($prompt, [
            'business_account_id' => $business->id,
            'request_type' => 'orchestrator_agent',
        ]);

        if (! $result['success']) {
            Log::error('OrchestratorAgent merge failed', ['error' => $result['error'] ?? 'Unknown']);

            return $this->buildFallbackBlueprint(
                $researchOutput,
                $trendOutput,
                $positioningOutput,
                $adOutput,
                $extraInputs
            );
        }

        $blueprint = $this->parseJsonOutput($result['content']);
        $blueprint = $this->ensureRequiredFields($blueprint, $researchOutput, $trendOutput, $positioningOutput, $adOutput);

        return $blueprint;
    }

    protected function buildFallbackBlueprint(
        array $researchOutput,
        array $trendOutput,
        array $positioningOutput,
        array $adOutput,
        array $extraInputs
    ): array {
        $trends = $trendOutput['trends'] ?? [];
        $pillars = $positioningOutput['messaging_pillars'] ?? [];
        $days = [];
        for ($i = 0; $i < 30; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $posts = [];
            $postCount = rand(1, 3);
            for ($j = 0; $j < $postCount; $j++) {
                $posts[] = [
                    'post_type' => ['education', 'engagement', 'story', 'promo', 'trend', 'authority'][$j % 6],
                    'hook' => 'Placeholder hook for '.$date,
                    'caption_draft' => 'Placeholder caption.',
                    'cta' => 'Learn more',
                    'hashtags' => [],
                    'visual_prompt' => '',
                    'quality_score' => 70,
                ];
            }
            $days[] = ['date' => $date, 'posts' => $posts];
        }

        $missingInputs = [];
        if (empty($extraInputs['ad_library_data'])) {
            $missingInputs[] = 'Competitor Ad Library data';
        }
        if (empty($extraInputs['web_search_results'])) {
            $missingInputs[] = 'Web search results';
        }

        return [
            'executive_summary' => 'Growth Blueprint generated from agent outputs. Orchestrator merge was skipped.',
            'market_map' => [
                'target_segments' => $researchOutput['market_segments'] ?? [],
                'pain_points' => $researchOutput['pain_points'] ?? [],
                'competitor_clusters' => $researchOutput['competitor_matrix'] ?? [],
            ],
            'trend_radar' => [
                'top_10_trends' => array_slice($trends, 0, 10),
                'sources' => $trendOutput['sources_used'] ?? [],
                'shelf_life_estimates' => [],
            ],
            'positioning_offer' => [
                'uvp' => $positioningOutput['uvp'] ?? '',
                'messaging_pillars' => $pillars,
                'offer_stack' => $positioningOutput['offer_stack'] ?? [],
                'headline_sets' => $positioningOutput['headline_sets'] ?? [],
            ],
            'content_calendar' => ['days' => $days],
            'ads_plan' => [
                'campaign_structure' => $adOutput['budget_pacing'] ?? [],
                'ad_set_hypotheses' => $adOutput['audience_hypotheses'] ?? [],
                'creatives' => $adOutput['creatives'] ?? [],
                'testing_schedule' => $adOutput['ab_test_plan'] ?? [],
            ],
            'measurement' => [
                'kpis' => ['reach', 'engagement', 'CTR', 'conversions'],
                'daily_monitor' => ['spend', 'impressions', 'clicks'],
                'weekly_monitor' => ['CPA', 'ROAS', 'engagement_rate'],
                'iteration_plan' => 'Review weekly; double down on winning creatives.',
            ],
            'disruptor_moves' => [
                'Offer innovation: differentiate with unique guarantee or bonus',
                'Content moat: build authority through consistent value content',
                'Community angle: leverage user-generated content and testimonials',
            ],
            'missing_inputs' => $missingInputs,
        ];
    }

    protected function ensureRequiredFields(array $blueprint, array $research, array $trend, array $positioning, array $ad): array
    {
        $blueprint['executive_summary'] = $blueprint['executive_summary'] ?? 'Growth Blueprint for market disruption.';
        $blueprint['market_map'] = $blueprint['market_map'] ?? [
            'target_segments' => $research['market_segments'] ?? [],
            'pain_points' => $research['pain_points'] ?? [],
            'competitor_clusters' => $research['competitor_matrix'] ?? [],
        ];
        $blueprint['trend_radar'] = $blueprint['trend_radar'] ?? [
            'top_10_trends' => $trend['trends'] ?? [],
            'sources' => $trend['sources_used'] ?? [],
            'shelf_life_estimates' => [],
        ];
        $blueprint['positioning_offer'] = $blueprint['positioning_offer'] ?? [
            'uvp' => $positioning['uvp'] ?? '',
            'messaging_pillars' => $positioning['messaging_pillars'] ?? [],
            'offer_stack' => $positioning['offer_stack'] ?? [],
            'headline_sets' => $positioning['headline_sets'] ?? [],
        ];
        $blueprint['content_calendar'] = $blueprint['content_calendar'] ?? ['days' => []];
        $blueprint['ads_plan'] = $blueprint['ads_plan'] ?? [
            'campaign_structure' => [],
            'ad_set_hypotheses' => $ad['audience_hypotheses'] ?? [],
            'creatives' => $ad['creatives'] ?? [],
            'testing_schedule' => $ad['ab_test_plan'] ?? [],
        ];
        $blueprint['measurement'] = $blueprint['measurement'] ?? [
            'kpis' => [],
            'daily_monitor' => [],
            'weekly_monitor' => [],
            'iteration_plan' => '',
        ];
        $blueprint['disruptor_moves'] = $blueprint['disruptor_moves'] ?? [];
        if (count($blueprint['disruptor_moves']) < 3) {
            $defaults = [
                'Offer innovation: differentiate with unique guarantee or bonus',
                'Content moat: build authority through consistent value content',
                'Community angle: leverage user-generated content',
            ];
            $blueprint['disruptor_moves'] = array_slice(array_merge($blueprint['disruptor_moves'], $defaults), 0, 3);
        }
        $blueprint['missing_inputs'] = $blueprint['missing_inputs'] ?? [];

        return $blueprint;
    }

    protected function loadPrompt(): string
    {
        return file_get_contents(resource_path('prompts/orchestrator.txt'));
    }

    protected function parseJsonOutput(string $content): array
    {
        $content = trim($content);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $m)) {
            $content = trim($m[1]);
        }
        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Orchestrator returned invalid JSON: '.json_last_error_msg());
        }

        return $decoded;
    }
}
