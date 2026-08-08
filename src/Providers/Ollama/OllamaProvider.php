<?php

declare(strict_types=1);

namespace WPAIOS\Providers\Ollama;

use WPAIOS\Providers\AbstractProvider;
use WPAIOS\Providers\Models\Request;
use WPAIOS\Providers\Models\Response;

/**
 * Local Ollama REST Provider Driver.
 */
class OllamaProvider extends AbstractProvider
{
    public function getName(): string
    {
        return 'ollama';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['tools', 'streaming'], true);
    }

    public function chat(Request $request): Response
    {
        $endpoint = rtrim($this->config['endpoint'] ?? 'http://127.0.0.1:11434', '/');
        $url = $endpoint . '/api/chat';
        $model = $request->model ?? ($this->config['default_model'] ?? 'llama3:latest');

        $body = [
            'model' => $model,
            'messages' => $request->messages,
            'stream' => false,
            'options' => [
                'temperature' => $request->temperature,
            ],
        ];

        $raw = $this->makeHttpRequest($url, [], $body);

        $content = $raw['message']['content'] ?? '';

        return new Response(
            content: $content,
            toolCalls: [],
            model: $model,
            usage: ['prompt_tokens' => 0, 'completion_tokens' => 0, 'total_tokens' => 0],
            finishReason: 'stop',
            rawResponse: $raw
        );
    }

    public function stream(Request $request, callable $callback): Response
    {
        return $this->chat($request);
    }
}
