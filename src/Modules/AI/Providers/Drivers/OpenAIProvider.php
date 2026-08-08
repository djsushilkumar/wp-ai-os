<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers\Drivers;

use Exception;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Models\ToolCall;
use WPAIOS\Modules\AI\Models\Usage;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;

/**
 * OpenAI Provider Driver (GPT-4o, GPT-4, GPT-3.5).
 */
class OpenAIProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'openai';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming', 'vision', 'tools', 'json_mode', 'embeddings', 'audio', 'image_gen'], true);
    }

    public function chat(Request $request): Response
    {
        $apiKey = $this->config['api_key'] ?? '';
        if (empty($apiKey)) {
            throw new Exception('OpenAI API Key is missing.');
        }

        $url = 'https://api.openai.com/v1/chat/completions';
        $model = $request->model ?? ($this->config['default_model'] ?? 'gpt-4o');

        $formattedMessages = array_map(fn ($m) => $m->toArray(), $request->messages);

        $body = [
            'model' => $model,
            'messages' => $formattedMessages,
            'temperature' => $request->temperature,
            'top_p' => $request->topP,
            'max_tokens' => $request->maxTokens,
        ];

        if ($request->jsonMode) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        if (!empty($request->tools)) {
            $body['tools'] = $request->tools;
        }

        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
        ];

        $raw = $this->postJson($url, $headers, $body);

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

        $usage = new Usage(
            promptTokens: $raw['usage']['prompt_tokens'] ?? 0,
            completionTokens: $raw['usage']['completion_tokens'] ?? 0,
            totalTokens: $raw['usage']['total_tokens'] ?? 0
        );

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
        return $this->chat($request);
    }
}
