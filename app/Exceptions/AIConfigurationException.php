<?php

namespace App\Exceptions;

use Exception;

class AIConfigurationException extends Exception
{
    public function __construct(
        string $message = 'No AI provider configured. Set OPENAI_API_KEY, GEMINI_API_KEY, or GROK_API_KEY in .env.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
