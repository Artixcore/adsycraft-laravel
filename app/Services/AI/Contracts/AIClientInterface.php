<?php

namespace App\Services\AI\Contracts;

interface AIClientInterface
{
    /**
     * @return array{success: bool, content: string, error?: string}
     */
    public function request(string $prompt, array $options = []): array;
}
