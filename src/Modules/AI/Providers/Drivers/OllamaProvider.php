<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers\Drivers;

use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Models\Usage;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;

/**
 * Local Ollama REST Provider Driver.
 */
class OllamaProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'ollama';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming', 'tools'], true);
    }

    public function chat(Request $request): Response
    {
        $endpoint = rtrim($this->config['endpoint'] ?? 'http://127.0.0.1:11434', '/');
        $url = $endpoint . '/api/chat';
        $model = $request->model ?? ($this->config['default_model'] ?? 'llama3:latest');

        $body = [
            'model' => $model,
            'messages' => array_map(fn ($m) => $m->toArray(), $request->messages),
            'stream' => false,
            'options' => [
                'temperature' => $request->temperature,
            ],
        ];

        $raw = $this->postJson($url, [], $body);
        $content = $raw['message']['content'] ?? '';

        return new Response(
            content: $content,
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
