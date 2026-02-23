<?php

namespace App\Services\AI;

use App\Exceptions\AIConfigurationException;
use App\Models\AiRequestLog;
use App\Services\AI\Contracts\AIClientInterface;
use Illuminate\Support\Facades\Log;

class AIManager
{
    /**
     * @return array<int, string>
     */
    public function getAvailableProviders(): array
    {
        $providers = config('ai.providers', []);
        $available = [];

        foreach ($providers as $name => $config) {
            $key = $config['key'] ?? null;
            if (is_string($key) && trim($key) !== '') {
                $available[] = $name;
            }
        }

        return $available;
    }

    public function hasConfiguredProvider(): bool
    {
        return count($this->getAvailableProviders()) > 0;
    }

    /**
     * @return array<int, string>
     */
    public function getProviderChain(): array
    {
        $available = $this->getAvailableProviders();

        if (empty($available)) {
            return [];
        }

        $fallbackChain = config('ai.fallback_chain', ['openai', 'gemini', 'grok']);
        $chain = [];

        foreach ($fallbackChain as $provider) {
            if (in_array($provider, $available, true)) {
                $chain[] = $provider;
            }
        }

        return $chain;
    }

    /**
     * @param  array{request_type?: string, model?: string, business_account_id?: int}  $options
     * @return array{success: bool, content: string, provider: string, error?: string}
     */
    public function execute(string $prompt, array $options = []): array
    {
        $chain = $this->getProviderChain();

        if (empty($chain)) {
            throw new AIConfigurationException;
        }

        $lastProvider = '';
        $lastError = '';

        foreach ($chain as $provider) {
            $lastProvider = $provider;
            $result = $this->executeWithProvider($provider, $prompt, $options);

            if ($result['success']) {
                return [
                    'success' => true,
                    'content' => $result['content'],
                    'provider' => $provider,
                ];
            }

            $lastError = $result['error'] ?? 'Unknown error';

            Log::warning('AI provider failed, trying fallback', [
                'provider' => $provider,
                'error' => $lastError,
            ]);
        }

        return [
            'success' => false,
            'content' => '',
            'provider' => $lastProvider,
            'error' => $lastError,
        ];
    }

    /**
     * @param  array{request_type?: string, model?: string, business_account_id?: int}  $options
     * @return array{success: bool, content: string, input_tokens?: int, output_tokens?: int, cost?: float, error?: string}
     */
    protected function executeWithProvider(string $provider, string $prompt, array $options = []): array
    {
        $client = $this->buildClient($provider);
        $start = microtime(true);
        $result = $client->request($prompt, $options);
        $latencyMs = (int) round((microtime(true) - $start) * 1000);

        $this->logRequest(
            $options['business_account_id'] ?? null,
            $provider,
            $options['model'] ?? $this->getModelForProvider($provider),
            $options['request_type'] ?? null,
            $result,
            $latencyMs
        );

        return $result;
    }

    protected function buildClient(string $provider): AIClientInterface
    {
        $providers = config('ai.providers', []);
        $config = $providers[$provider] ?? null;

        if (! $config || empty($config['key'])) {
            throw new AIConfigurationException("Provider {$provider} is not configured.");
        }

        $key = $config['key'];
        $model = $config['model'] ?? null;

        return match ($provider) {
            'openai' => new OpenAIClient($key, $model),
            'gemini' => new GeminiClient($key, $model),
            'grok' => new GrokClient($key, $model),
            default => throw new \InvalidArgumentException('Unknown provider: '.$provider),
        };
    }

    protected function getModelForProvider(string $provider): ?string
    {
        return config("ai.providers.{$provider}.model");
    }

    protected function logRequest(
        ?int $businessAccountId,
        string $provider,
        ?string $model,
        ?string $requestType,
        array $result,
        int $latencyMs
    ): void {
        AiRequestLog::create([
            'business_account_id' => $businessAccountId,
            'provider' => $provider,
            'model' => $model,
            'request_type' => $requestType,
            'input_tokens' => $result['input_tokens'] ?? null,
            'output_tokens' => $result['output_tokens'] ?? null,
            'cost' => $result['cost'] ?? null,
            'status' => ($result['success'] ?? false) ? 'success' : 'failed',
            'latency_ms' => $latencyMs,
        ]);
    }
}
