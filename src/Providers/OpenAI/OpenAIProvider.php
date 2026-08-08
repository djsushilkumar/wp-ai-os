<?php

declare(strict_types=1);

namespace WPAIOS\Providers\OpenAI;

use Exception;
use WPAIOS\Providers\AbstractProvider;
use WPAIOS\Providers\Models\Request;
use WPAIOS\Providers\Models\Response;
use WPAIOS\Providers\Models\ToolCall;

/**
 * OpenAI LLM Provider Driver.
 */
class OpenAIProvider extends AbstractProvider
{
    public function getName(): string
    {
        return 'openai';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['tools', 'streaming', 'json_mode', 'vision'], true);
    }

    public function chat(Request $request): Response
    {
        $apiKey = $this->config['api_key'] ?? '';
        if (empty($apiKey)) {
            throw new Exception('OpenAI API Key is missing.');
        }

        $url = 'https://api.openai.com/v1/chat/completions';
        $model = $request->model ?? ($this->config['default_model'] ?? 'gpt-4o');

        $body = [
            'model' => $model,
            'messages' => $request->messages,
            'temperature' => $request->temperature,
            'max_tokens' => $request->maxTokens,
        ];

        if (!empty($request->tools)) {
            $body['tools'] = $request->tools;
        }

        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
        ];

        $raw = $this->makeHttpRequest($url, $headers, $body);

        $choice = $raw['choices'][0]['message'] ?? [];
        $content = $choice['content'] ?? '';
        $finishReason = $raw['choices'][0]['finish_reason'] ?? 'stop';

        $toolCalls = [];
        if (!empty($choice['tool_calls'])) {
            foreach ($choice['tool_calls'] as $tc) {
                $args = json_decode($tc['function']['arguments'] ?? '{}', true);
                $toolCalls[] = new ToolCall(
                    id: $tc['id'] ?? uniqid('tc_'),
                    name: $tc['function']['name'] ?? '',
                    arguments: is_array($args) ? $args : []
                );
            }
        }

        $usage = [
            'prompt_tokens' => $raw['usage']['prompt_tokens'] ?? 0,
            'completion_tokens' => $raw['usage']['completion_tokens'] ?? 0,
            'total_tokens' => $raw['usage']['total_tokens'] ?? 0,
        ];

        return new Response(
            content: $content,
            toolCalls: $toolCalls,
            model: $model,
            usage: $usage,
            finishReason: $finishReason,
            rawResponse: $raw
        );
    }

    public function stream(Request $request, callable $callback): Response
    {
        // Fallback to chat completion if streaming transport is not available in non-SSE environment
        return $this->chat($request);
    }
}
