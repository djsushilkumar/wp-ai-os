<?xml version="1.0" encoding="UTF-8"?>
<?php

declare(strict_types=1);

return [
    'version' => '1.0.0',
    'environment' => 'production',
    'providers' => [
        'default' => 'openai',
        'fallback_chain' => ['openai', 'anthropic', 'gemini', 'ollama'],
        'openai' => [
            'api_key' => '',
            'default_model' => 'gpt-4o',
            'timeout' => 30,
        ],
        'anthropic' => [
            'api_key' => '',
            'default_model' => 'claude-3-5-sonnet-20240620',
            'timeout' => 30,
        ],
        'gemini' => [
            'api_key' => '',
            'default_model' => 'gemini-1.5-pro',
            'timeout' => 30,
        ],
        'ollama' => [
            'endpoint' => 'http://127.0.0.1:11434',
            'default_model' => 'llama3:latest',
            'timeout' => 60,
        ],
    ],
    'mcp' => [
        'enabled' => true,
        'route_namespace' => 'wp-ai-os/v1',
        'transports' => [
            'http' => true,
            'sse' => true,
            'cli' => true,
        ],
    ],
    'security' => [
        'require_auth' => true,
        'minimum_capability' => 'manage_options',
        'rate_limit_per_minute' => 120,
        'daily_token_quota' => 500000,
        'sandbox_mode' => false,
    ],
    'audit' => [
        'enabled' => true,
        'retention_days' => 90,
    ],
];
