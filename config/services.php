<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'meta' => [
        'app_id' => env('META_APP_ID'),
        'app_secret' => env('META_APP_SECRET'),
        'redirect_uri' => env('META_REDIRECT_URI', env('APP_URL').'/connectors/meta/callback'),
        'webhook_verify_token' => env('META_WEBHOOK_VERIFY_TOKEN'),
        'graph_version' => env('META_GRAPH_VERSION', 'v21.0'),
        'stub' => env('META_CONNECTOR_STUB', false),
        'ad_library' => [
            'enabled' => env('META_AD_LIBRARY_ENABLED', false),
            'default_country' => env('META_AD_LIBRARY_DEFAULT_COUNTRY', 'US'),
            'cache_ttl_seconds' => env('META_AD_LIBRARY_CACHE_TTL_SECONDS', 3600),
            'rate_limit_backoff' => env('META_AD_LIBRARY_RATE_LIMIT_BACKOFF', 2),
            'access_token' => env('META_AD_LIBRARY_ACCESS_TOKEN'),
        ],
    ],

];
