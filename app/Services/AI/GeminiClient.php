<?php

namespace App\Services\AI;

use App\Services\AI\Contracts\AIClientInterface;

class GeminiClient implements AIClientInterface
{
    public function __construct(
        protected string $apiKey,
        protected ?string $model = null
    ) {}

    public function request(string $prompt, array $options = []): array
    {
        $preview = strlen($prompt) > 50 ? substr($prompt, 0, 50) . '…' : $prompt;

        return [
            'success' => true,
            'content' => 'Stub Gemini response for: ' . $preview,
        ];
    }
}
