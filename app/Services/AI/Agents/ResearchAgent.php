<?php

namespace App\Services\AI\Agents;

use App\Models\BusinessAccount;
use App\Services\AI\AIManager;
use App\Services\AI\Memory\SharedMemoryService;
use Illuminate\Support\Facades\Log;

class ResearchAgent
{
    public function __construct(
        private readonly AIManager $aiManager,
        private readonly SharedMemoryService $memory
    ) {}

    public function run(BusinessAccount $business, array $extraInputs = []): array
    {
        $context = $this->memory->getFullContext($business);

        $prompt = $this->loadPrompt();
        $prompt = str_replace(
            [
                '{{business_name}}',
                '{{niche}}',
                '{{business_profile}}',
                '{{competitor_urls}}',
                '{{ad_library_data}}',
                '{{web_search_results}}',
            ],
            [
                $business->name ?? 'Business',
                $business->niche ?? 'general',
                json_encode($context['business_profile'], JSON_PRETTY_PRINT),
                json_encode($context['competitor_urls'], JSON_PRETTY_PRINT),
                json_encode($extraInputs['ad_library_data'] ?? [], JSON_PRETTY_PRINT),
                $extraInputs['web_search_results'] ?? 'No web search results provided.',
            ],
            $prompt
        );

        $result = $this->aiManager->execute($prompt, [
            'business_account_id' => $business->id,
            'request_type' => 'research_agent',
        ]);

        if (! $result['success']) {
            Log::error('ResearchAgent failed', ['error' => $result['error'] ?? 'Unknown']);
            throw new \RuntimeException('Research agent failed: '.($result['error'] ?? 'Unknown error'));
        }

        return $this->parseJsonOutput($result['content']);
    }

    protected function loadPrompt(): string
    {
        return file_get_contents(resource_path('prompts/research.txt'));
    }

    protected function parseJsonOutput(string $content): array
    {
        $content = trim($content);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $m)) {
            $content = trim($m[1]);
        }
        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Research agent returned invalid JSON: '.json_last_error_msg());
        }

        return $decoded;
    }
}
