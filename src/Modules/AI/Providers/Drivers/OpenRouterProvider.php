<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers\Drivers;

use Exception;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Models\Usage;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;

/**
 * OpenRouter Unified Multi-LLM API Provider Driver.
 */
class OpenRouterProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'openrouter';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming', 'tools', 'json_mode'], true);
    }

    public function chat(Request $request): Response
    {
        $apiKey = $this->config['api_key'] ?? '';
        if (empty($apiKey)) {
            throw new Exception('OpenRouter API Key is missing.');
        }

        $url = 'https://openrouter.ai/api/v1/chat/completions';
        $model = $request->model ?? ($this->config['default_model'] ?? 'meta-llama/llama-3.1-70b-instruct');

        $body = [
            'model' => $model,
            'messages' => array_map(fn ($m) => $m->toArray(), $request->messages),
            'temperature' => $request->temperature,
            'max_tokens' => $request->maxTokens,
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
            'HTTP-Referer' => function_exists('site_url') ? site_url() : 'https://wp-ai-os.io',
            'X-Title' => 'WP AI OS',
        ];

        $raw = $this->postJson($url, $headers, $body);

        $text = $raw['choices'][0]['message']['content'] ?? '';

        return new Response(
            content: $text,
            toolCalls: [],
            model: $model,
            usage: new Usage(),
            finishReason: 'stop',
            rawResponse: $raw
        );
    }

    public function stream(Request $request, callable $callback): Response
    {
        return $this->chat($request);
    }
}
