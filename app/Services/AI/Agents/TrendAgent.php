<?php

namespace App\Services\AI\Agents;

use App\Models\BusinessAccount;
use App\Services\AI\AIManager;
use App\Services\AI\Memory\SharedMemoryService;
use Illuminate\Support\Facades\Log;

class TrendAgent
{
    public function __construct(
        private readonly AIManager $aiManager,
        private readonly SharedMemoryService $memory
    ) {}

    public function run(BusinessAccount $business, array $extraInputs = []): array
    {
        $context = $this->memory->getFullContext($business);
        $profile = $context['business_profile'];

        $prompt = $this->loadPrompt();
        $prompt = str_replace(
            [
                '{{business_name}}',
                '{{niche}}',
                '{{business_profile}}',
                '{{target_locations}}',
                '{{language}}',
                '{{brand_voice_summary}}',
                '{{web_search_results}}',
            ],
            [
                $business->name ?? 'Business',
                $business->niche ?? 'general',
                json_encode($profile, JSON_PRETTY_PRINT),
                json_encode($profile['target_locations'] ?? ['US']),
                $profile['language'] ?? 'en',
                $context['brand_voice_summary'],
                $extraInputs['web_search_results'] ?? 'No web search results provided.',
            ],
            $prompt
        );

        $result = $this->aiManager->execute($prompt, [
            'business_account_id' => $business->id,
            'request_type' => 'trend_agent',
        ]);

        if (! $result['success']) {
            Log::error('TrendAgent failed', ['error' => $result['error'] ?? 'Unknown']);
            throw new \RuntimeException('Trend agent failed: '.($result['error'] ?? 'Unknown error'));
        }

        return $this->parseJsonOutput($result['content']);
    }

    protected function loadPrompt(): string
    {
        return file_get_contents(resource_path('prompts/trend.txt'));
    }

    protected function parseJsonOutput(string $content): array
    {
        $content = trim($content);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $m)) {
            $content = trim($m[1]);
        }
        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Trend agent returned invalid JSON: '.json_last_error_msg());
        }

        return $decoded;
    }
}
