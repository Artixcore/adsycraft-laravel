<?php

namespace App\Services\AI\Agents;

use App\Models\BusinessAccount;
use App\Services\AI\AIManager;
use App\Services\AI\Memory\SharedMemoryService;
use Illuminate\Support\Facades\Log;

class PositioningAgent
{
    public function __construct(
        private readonly AIManager $aiManager,
        private readonly SharedMemoryService $memory
    ) {}

    public function run(BusinessAccount $business, array $priorOutputs): array
    {
        $context = $this->memory->getFullContext($business);

        $prompt = $this->loadPrompt();
        $prompt = str_replace(
            [
                '{{business_name}}',
                '{{niche}}',
                '{{business_profile}}',
                '{{brand_voice}}',
                '{{research_output}}',
                '{{trend_output}}',
            ],
            [
                $business->name ?? 'Business',
                $business->niche ?? 'general',
                json_encode($context['business_profile'], JSON_PRETTY_PRINT),
                json_encode($context['brand_voice'] ?? [], JSON_PRETTY_PRINT),
                json_encode($priorOutputs['research_output'] ?? [], JSON_PRETTY_PRINT),
                json_encode($priorOutputs['trend_output'] ?? [], JSON_PRETTY_PRINT),
            ],
            $prompt
        );

        $result = $this->aiManager->execute($prompt, [
            'business_account_id' => $business->id,
            'request_type' => 'positioning_agent',
        ]);

        if (! $result['success']) {
            Log::error('PositioningAgent failed', ['error' => $result['error'] ?? 'Unknown']);
            throw new \RuntimeException('Positioning agent failed: '.($result['error'] ?? 'Unknown error'));
        }

        return $this->parseJsonOutput($result['content']);
    }

    protected function loadPrompt(): string
    {
        return file_get_contents(resource_path('prompts/positioning.txt'));
    }

    protected function parseJsonOutput(string $content): array
    {
        $content = trim($content);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $m)) {
            $content = trim($m[1]);
        }
        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Positioning agent returned invalid JSON: '.json_last_error_msg());
        }

        return $decoded;
    }
}
