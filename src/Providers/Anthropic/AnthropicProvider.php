<?php

declare(strict_types=1);

namespace WPAIOS\Providers\Anthropic;

use Exception;
use WPAIOS\Providers\AbstractProvider;
use WPAIOS\Providers\Models\Request;
use WPAIOS\Providers\Models\Response;
use WPAIOS\Providers\Models\ToolCall;

/**
 * Anthropic Claude Provider Driver.
 */
class AnthropicProvider extends AbstractProvider
{
    public function getName(): string
    {
        return 'anthropic';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['tools', 'streaming', 'vision'], true);
    }

    public function chat(Request $request): Response
    {
        $apiKey = $this->config['api_key'] ?? '';
        if (empty($apiKey)) {
            throw new Exception('Anthropic API Key is missing.');
        }

        $url = 'https://api.anthropic.com/v1/messages';
        $model = $request->model ?? ($this->config['default_model'] ?? 'claude-3-5-sonnet-20240620');

        $systemPrompt = '';
        $filteredMessages = [];

        foreach ($request->messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemPrompt = is_string($msg['content']) ? $msg['content'] : '';
            } else {
                $filteredMessages[] = $msg;
            }
        }

        $body = [
            'model' => $model,
            'messages' => $filteredMessages,
            'max_tokens' => $request->maxTokens,
            'temperature' => $request->temperature,
        ];

        if (!empty($systemPrompt)) {
            $body['system'] = $systemPrompt;
        }

        if (!empty($request->tools)) {
            $body['tools'] = array_map(function ($tool) {
                return [
                    'name' => $tool['function']['name'] ?? $tool['name'] ?? '',
                    'description' => $tool['function']['description'] ?? $tool['description'] ?? '',
                    'input_schema' => $tool['function']['parameters'] ?? $tool['parameters'] ?? [],
                ];
            }, $request->tools);
        }

        $headers = [
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
        ];

        $raw = $this->makeHttpRequest($url, $headers, $body);

        $content = '';
        $toolCalls = [];

        if (!empty($raw['content'])) {
            foreach ($raw['content'] as $block) {
                if ($block['type'] === 'text') {
                    $content .= $block['text'];
                } elseif ($block['type'] === 'tool_use') {
                    $toolCalls[] = new ToolCall(
                        id: $block['id'] ?? uniqid('anth_tc_'),
                        name: $block['name'] ?? '',
                        arguments: $block['input'] ?? []
                    );
                }
            }
        }

        $usage = [
            'prompt_tokens' => $raw['usage']['input_tokens'] ?? 0,
            'completion_tokens' => $raw['usage']['output_tokens'] ?? 0,
            'total_tokens' => ($raw['usage']['input_tokens'] ?? 0) + ($raw['usage']['output_tokens'] ?? 0),
        ];

        return new Response(
            content: $content,
            toolCalls: $toolCalls,
            model: $model,
            usage: $usage,
            finishReason: $raw['stop_reason'] ?? 'stop',
            rawResponse: $raw
        );
    }

    public function stream(Request $request, callable $callback): Response
    {
        return $this->chat($request);
    }
}
