<?php

namespace App\Services\AI;

use App\Models\AiConnection;
use App\Models\AiRequestLog;
use App\Models\BusinessAccount;
use App\Services\AI\Contracts\AIClientInterface;

class AITaskRouter
{
    public function execute(BusinessAccount $business, string $prompt, array $options = []): array
    {
        $primary = $business->aiConnections()->primary()->enabled()->first();

        if (! $primary) {
            return [
                'success' => false,
                'content' => '',
                'provider' => '',
            ];
        }

        $result = $this->executeWithConnection($business, $primary, $prompt, $options);

        if ($result['success']) {
            return [
                'success' => true,
                'content' => $result['content'],
                'provider' => $primary->provider,
            ];
        }

        $fallbacks = $business->aiConnections()
            ->enabled()
            ->where('id', '!=', $primary->id)
            ->orderBy('provider')
            ->get();

        foreach ($fallbacks as $connection) {
            $result = $this->executeWithConnection($business, $connection, $prompt, $options);
            if ($result['success']) {
                return [
                    'success' => true,
                    'content' => $result['content'],
                    'provider' => $connection->provider,
                ];
            }
        }

        return [
            'success' => false,
            'content' => '',
            'provider' => $primary->provider,
        ];
    }

    protected function executeWithConnection(BusinessAccount $business, AiConnection $connection, string $prompt, array $options = []): array
    {
        $client = $this->buildClient($connection);
        $start = microtime(true);
        $result = $client->request($prompt, $options);
        $latencyMs = (int) round((microtime(true) - $start) * 1000);

        $this->logRequest($business->id, $connection, $options['request_type'] ?? null, $result, $latencyMs);

        return $result;
    }

    protected function logRequest(?int $businessAccountId, AiConnection $connection, ?string $requestType, array $result, int $latencyMs): void
    {
        AiRequestLog::create([
            'business_account_id' => $businessAccountId,
            'provider' => $connection->provider,
            'model' => $connection->default_model,
            'request_type' => $requestType,
            'input_tokens' => $result['input_tokens'] ?? null,
            'output_tokens' => $result['output_tokens'] ?? null,
            'cost' => $result['cost'] ?? null,
            'status' => ($result['success'] ?? false) ? 'success' : 'failed',
            'latency_ms' => $latencyMs,
        ]);
    }

    protected function buildClient(AiConnection $connection): AIClientInterface
    {
        $key = $connection->api_key;
        $model = $connection->default_model;

        return match ($connection->provider) {
            'openai' => new OpenAIClient($key, $model),
            'gemini' => new GeminiClient($key, $model),
            'grok' => new GrokClient($key, $model),
            default => throw new \InvalidArgumentException('Unknown provider: '.$connection->provider),
        };
    }
}
