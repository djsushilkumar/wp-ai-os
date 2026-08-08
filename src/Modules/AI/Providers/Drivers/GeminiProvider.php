<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers\Drivers;

use Exception;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Models\Usage;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;

/**
 * Google Gemini Provider Driver.
 */
class GeminiProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'gemini';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming', 'vision', 'tools'], true);
    }

    public function chat(Request $request): Response
    {
        $apiKey = $this->config['api_key'] ?? '';
        if (empty($apiKey)) {
            throw new Exception('Google Gemini API Key is missing.');
        }

        $model = $request->model ?? ($this->config['default_model'] ?? 'gemini-1.5-pro');
        $url = sprintf('https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s', $model, $apiKey);

        $contents = [];
        foreach ($request->messages as $msg) {
            $role = ($msg->role === 'assistant') ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => is_string($msg->content) ? $msg->content : '']
                ]
            ];
        }

        $body = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $request->temperature,
                'topP' => $request->topP,
                'maxOutputTokens' => $request->maxTokens,
            ]
        ];

        $raw = $this->postJson($url, [], $body);
        $text = $raw['candidates'][0]['content']['parts'][0]['text'] ?? '';

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
