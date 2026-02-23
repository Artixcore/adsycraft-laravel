<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Provider Configuration
    |--------------------------------------------------------------------------
    |
    | API keys are loaded from environment variables. Never use env() outside
    | config files. Keys are never exposed to the frontend or stored in DB.
    |
    */

    'providers' => [
        'openai' => [
            'key' => env('OPENAI_API_KEY'),
            'model' => env('AI_OPENAI_MODEL', 'gpt-4o'),
        ],
        'gemini' => [
            'key' => env('GEMINI_API_KEY'),
            'model' => env('AI_GEMINI_MODEL', 'gemini-1.5-pro'),
        ],
        'grok' => [
            'key' => env('GROK_API_KEY'),
            'model' => env('AI_GROK_MODEL', 'grok-2'),
        ],
    ],

    'default_provider' => env('AI_DEFAULT_PROVIDER', 'openai'),

    'fallback_chain' => ['openai', 'gemini', 'grok'],

];
