<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers\Drivers;

use Exception;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Models\Usage;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;

/**
 * Azure OpenAI Service Provider Driver.
 */
class AzureOpenAIProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'azure_openai';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming', 'tools', 'json_mode'], true);
    }

    public function chat(Request $request): Response
    {
        $resourceName = $this->config['resource_name'] ?? '';
        $deploymentId = $this->config['deployment_id'] ?? '';
        $apiKey = $this->config['api_key'] ?? '';
        $apiVersion = $this->config['api_version'] ?? '2024-02-15-preview';

        if (empty($resourceName) || empty($deploymentId) || empty($apiKey)) {
            throw new Exception('Azure OpenAI configuration parameters (resource_name, deployment_id, api_key) missing.');
        }

        $url = sprintf('https://%s.openai.azure.com/openai/deployments/%s/chat/completions?api-version=%s', $resourceName, $deploymentId, $apiVersion);

        $body = [
            'messages' => array_map(fn ($m) => $m->toArray(), $request->messages),
            'temperature' => $request->temperature,
            'max_tokens' => $request->maxTokens,
        ];

        $headers = [
            'api-key' => $apiKey,
        ];

        $raw = $this->postJson($url, $headers, $body);
        $content = $raw['choices'][0]['message']['content'] ?? '';

        return new Response(
            content: $content,
            toolCalls: [],
            model: $deploymentId,
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
