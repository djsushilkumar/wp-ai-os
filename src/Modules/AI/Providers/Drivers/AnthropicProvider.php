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
 * Anthropic Claude Provider Driver (Claude 3.5 Sonnet, Claude 3 Opus, Claude 3 Haiku).
 */
class AnthropicProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'anthropic';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming', 'vision', 'tools'], true);
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
            if ($msg->role === 'system' || $msg->role === 'developer') {
                $systemPrompt = is_string($msg->content) ? $msg->content : '';
            } else {
                $filteredMessages[] = $msg->toArray();
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
            $body['tools'] = array_map(static function ($tool) {
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

        $raw = $this->postJson($url, $headers, $body);

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

        $promptTokens = $raw['usage']['input_tokens'] ?? 0;
        $compTokens = $raw['usage']['output_tokens'] ?? 0;
        $usage = new Usage($promptTokens, $compTokens, $promptTokens + $compTokens);

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
