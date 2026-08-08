<?php

declare(strict_types=1);

namespace WPAIOS\Providers\Gemini;

use Exception;
use WPAIOS\Providers\AbstractProvider;
use WPAIOS\Providers\Models\Request;
use WPAIOS\Providers\Models\Response;

/**
 * Google Gemini LLM Provider Driver.
 */
class GeminiProvider extends AbstractProvider
{
    public function getName(): string
    {
        return 'gemini';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['tools', 'streaming', 'vision'], true);
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
            $role = ($msg['role'] === 'assistant') ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => is_string($msg['content']) ? $msg['content'] : '']
                ]
            ];
        }

        $body = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $request->temperature,
                'maxOutputTokens' => $request->maxTokens,
            ]
        ];

        $raw = $this->makeHttpRequest($url, [], $body);

        $text = $raw['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return new Response(
            content: $text,
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
