<?php

namespace App\Services\AI;

use App\Models\AiConnection;
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

        $result = $this->executeWithConnection($primary, $prompt, $options);

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
            $result = $this->executeWithConnection($connection, $prompt, $options);
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

    protected function executeWithConnection(AiConnection $connection, string $prompt, array $options): array
    {
        $client = $this->buildClient($connection);

        return $client->request($prompt, $options);
    }

    protected function buildClient(AiConnection $connection): AIClientInterface
    {
        $key = $connection->api_key;
        $model = $connection->default_model;

        return match ($connection->provider) {
            'openai' => new OpenAIClient($key, $model),
            'gemini' => new GeminiClient($key, $model),
            'grok' => new GrokClient($key, $model),
            default => throw new \InvalidArgumentException('Unknown provider: ' . $connection->provider),
        };
    }
}
