<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers\Drivers;

use Exception;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Models\Usage;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;

/**
 * Google Cloud Vertex AI Provider Driver.
 */
class VertexAIProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'vertex_ai';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming', 'vision'], true);
    }

    public function chat(Request $request): Response
    {
        $projectId = $this->config['project_id'] ?? '';
        $location = $this->config['location'] ?? 'us-central1';
        $accessToken = $this->config['access_token'] ?? '';
        $model = $request->model ?? ($this->config['default_model'] ?? 'gemini-1.5-pro');

        if (empty($projectId) || empty($accessToken)) {
            throw new Exception('Google Cloud Vertex AI configuration parameters (project_id, access_token) missing.');
        }

        $url = sprintf('https://%s-aiplatform.googleapis.com/v1/projects/%s/locations/%s/publishers/google/models/%s:generateContent', $location, $projectId, $location, $model);

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
                'maxOutputTokens' => $request->maxTokens,
            ]
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $raw = $this->postJson($url, $headers, $body);
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
