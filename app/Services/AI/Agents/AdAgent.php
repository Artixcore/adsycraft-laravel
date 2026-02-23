<?php

namespace App\Services\AI\Agents;

use App\Models\BusinessAccount;
use App\Services\AI\AIManager;
use App\Services\AI\Memory\SharedMemoryService;
use Illuminate\Support\Facades\Log;

class AdAgent
{
    public function __construct(
        private readonly AIManager $aiManager,
        private readonly SharedMemoryService $memory
    ) {}

    /**
     * Run ad plan generation. Requires research, trend, positioning outputs and optional ad_library_data.
     *
     * @param  array{research_output: array, trend_output: array, positioning_output: array, ad_library_data?: array}  $priorOutputs
     * @return array<string, mixed>
     */
    public function run(BusinessAccount $business, array $priorOutputs): array
    {
        $context = $this->memory->getFullContext($business);
        $profile = $context['business_profile'];
        $settings = $business->settings ?? [];

        $goals = $profile['goals'] ?? $settings['goals'] ?? ['leads', 'sales'];
        $budgetMin = $profile['budget_range_min'] ?? $settings['budget_range_min'] ?? null;
        $budgetMax = $profile['budget_range_max'] ?? $settings['budget_range_max'] ?? null;
        $budgetRange = $budgetMin && $budgetMax
            ? "\${$budgetMin}-\${$budgetMax}/day"
            : 'Not specified. Recommend starting with $50-100/day for testing.';

        $prompt = $this->loadPrompt();
        $prompt = str_replace(
            [
                '{{business_name}}',
                '{{niche}}',
                '{{business_profile}}',
                '{{goals}}',
                '{{budget_range}}',
                '{{research_output}}',
                '{{trend_output}}',
                '{{positioning_output}}',
                '{{ad_library_data}}',
            ],
            [
                $business->name ?? 'Business',
                $business->niche ?? 'general',
                json_encode($profile, JSON_PRETTY_PRINT),
                json_encode($goals),
                $budgetRange,
                json_encode($priorOutputs['research_output'] ?? [], JSON_PRETTY_PRINT),
                json_encode($priorOutputs['trend_output'] ?? [], JSON_PRETTY_PRINT),
                json_encode($priorOutputs['positioning_output'] ?? [], JSON_PRETTY_PRINT),
                json_encode($priorOutputs['ad_library_data'] ?? [], JSON_PRETTY_PRINT),
            ],
            $prompt
        );

        $result = $this->aiManager->execute($prompt, [
            'business_account_id' => $business->id,
            'request_type' => 'ad_agent',
        ]);

        if (! $result['success']) {
            Log::error('AdAgent failed', ['error' => $result['error'] ?? 'Unknown']);

            throw new \RuntimeException('Ad agent failed: '.($result['error'] ?? 'Unknown error'));
        }

        return $this->parseJsonOutput($result['content']);
    }

    protected function loadPrompt(): string
    {
        $path = resource_path('prompts/ad.txt');

        return file_get_contents($path);
    }

    /**
     * @return array<string, mixed>
     */
    protected function parseJsonOutput(string $content): array
    {
        $content = trim($content);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $m)) {
            $content = trim($m[1]);
        }

        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Ad agent returned invalid JSON: '.json_last_error_msg());
        }

        return $decoded;
    }
}
