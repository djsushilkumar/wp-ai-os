<?php

declare(strict_types=1);

namespace WPAIOS\Providers;

/**
 * Abstract Base Class for AI Providers handling common HTTP configuration and logging.
 */
abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(protected array $config = [])
    {
    }

    /**
     * Helper method to send HTTP POST requests using WordPress wp_remote_post or cURL fallback.
     *
     * @param string $url
     * @param array<string, string> $headers
     * @param array<string, mixed> $body
     * @return array<string, mixed>
     * @throws \Exception
     */
    protected function makeHttpRequest(string $url, array $headers, array $body): array
    {
        $payload = [
            'headers' => array_merge(['Content-Type' => 'application/json'], $headers),
            'body' => json_encode($body),
            'timeout' => $this->config['timeout'] ?? 30,
        ];

        if (function_exists('wp_remote_post')) {
            $response = wp_remote_post($url, $payload);

            if (is_wp_error($response)) {
                throw new \Exception('HTTP Request Error: ' . $response->get_error_message());
            }

            $statusCode = wp_remote_retrieve_response_code($response);
            $responseBody = wp_remote_retrieve_body($response);

            if ($statusCode >= 400) {
                throw new \Exception(sprintf('Provider API Error [%d]: %s', $statusCode, $responseBody));
            }

            $data = json_decode($responseBody, true);
            return is_array($data) ? $data : [];
        }

        // Fallback for isolated CLI / unit test execution without full WP boot
        $ch = curl_init($url);
        $formattedHeaders = [];
        foreach ($payload['headers'] as $k => $v) {
            $formattedHeaders[] = "$k: $v";
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $formattedHeaders,
            CURLOPT_POSTFIELDS => $payload['body'],
            CURLOPT_TIMEOUT => $payload['timeout'],
        ]);

        $result = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400 || false === $result) {
            throw new \Exception(sprintf('HTTP Error [%d]', $httpCode));
        }

        $decoded = json_decode((string) $result, true);
        return is_array($decoded) ? $decoded : [];
    }
}
