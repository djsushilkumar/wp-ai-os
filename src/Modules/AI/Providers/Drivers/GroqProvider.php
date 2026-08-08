<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers\Drivers;

use Exception;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Models\Usage;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;

/**
 * Groq LPU High-Speed LLM Provider Driver.
 */
class GroqProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'groq';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming', 'tools', 'json_mode'], true);
    }

    public function chat(Request $request): Response
    {
        $apiKey = $this->config['api_key'] ?? '';
        if (empty($apiKey)) {
            throw new Exception('Groq API Key is missing.');
        }

        $url = 'https://api.groq.com/openai/v1/chat/completions';
        $model = $request->model ?? ($this->config['default_model'] ?? 'llama-3.1-70b-versatile');

        $body = [
            'model' => $model,
            'messages' => array_map(fn ($m) => $m->toArray(), $request->messages),
            'temperature' => $request->temperature,
            'max_tokens' => $request->maxTokens,
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
        ];

        $raw = $this->postJson($url, $headers, $body);
        $text = $raw['choices'][0]['message']['content'] ?? '';

        return new Response(
            content: $text,
            toolCalls: [],
            model: $model,
            usage: new Usage($raw['usage']['prompt_tokens'] ?? 0, $raw['usage']['completion_tokens'] ?? 0, $raw['usage']['total_tokens'] ?? 0),
            finishReason: 'stop',
            rawResponse: $raw
        );
    }

    public function stream(Request $request, callable $callback): Response
    {
        return $this->chat($request);
    }
}
