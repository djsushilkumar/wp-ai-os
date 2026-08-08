<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers;

use Exception;
use WPAIOS\Modules\AI\Contracts\AIProviderInterface;
use WPAIOS\Support\Http;

/**
 * Abstract Base Class for all AI Model Providers.
 */
abstract class AbstractAIProvider implements AIProviderInterface
{
    protected Http $http;

    /**
     * @param array<string, mixed> $config
     * @param Http|null $http
     */
    public function __construct(
        protected array $config = [],
        ?Http $http = null
    ) {
        $this->http = $http ?? new Http();
    }

    /**
     * Helper to make HTTP POST requests.
     *
     * @param string $url
     * @param array<string, string> $headers
     * @param array<string, mixed> $body
     * @return array<string, mixed>
     * @throws Exception
     */
    protected function postJson(string $url, array $headers, array $body): array
    {
        $res = $this->http->post($url, $body, $headers, $this->config['timeout'] ?? 30);
        if ($res['status'] >= 400) {
            throw new Exception(sprintf('Provider [%s] HTTP Error [%d]: %s', $this->getName(), $res['status'], $res['body']));
        }

        $decoded = json_decode($res['body'], true);
        return is_array($decoded) ? $decoded : [];
    }
}
