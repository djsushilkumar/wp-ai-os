<?php

declare(strict_types=1);

namespace WPAIOS\Support;

use Exception;

/**
 * Enterprise HTTP Client wrapping WP HTTP API with cURL fallback.
 */
class Http
{
    /**
     * Send HTTP GET Request.
     *
     * @param string $url
     * @param array<string, string> $headers
     * @param int $timeout
     * @return array{status: int, body: string, headers: array<string, string>}
     * @throws Exception
     */
    public function get(string $url, array $headers = [], int $timeout = 30): array
    {
        return $this->request('GET', $url, [], $headers, $timeout);
    }

    /**
     * Send HTTP POST Request.
     *
     * @param string $url
     * @param array<string, mixed> $body
     * @param array<string, string> $headers
     * @param int $timeout
     * @return array{status: int, body: string, headers: array<string, string>}
     * @throws Exception
     */
    public function post(string $url, array $body = [], array $headers = [], int $timeout = 30): array
    {
        return $this->request('POST', $url, $body, $headers, $timeout);
    }

    /**
     * Send HTTP Request.
     *
     * @param string $method
     * @param string $url
     * @param array<string, mixed> $body
     * @param array<string, string> $headers
     * @param int $timeout
     * @return array{status: int, body: string, headers: array<string, string>}
     * @throws Exception
     */
    public function request(string $method, string $url, array $body = [], array $headers = [], int $timeout = 30): array
    {
        if (function_exists('wp_remote_request')) {
            $formattedHeaders = array_merge(['Content-Type' => 'application/json'], $headers);
            $args = [
                'method' => strtoupper($method),
                'headers' => $formattedHeaders,
                'timeout' => $timeout,
            ];

            if (!empty($body)) {
                $args['body'] = json_encode($body);
            }

            $response = wp_remote_request($url, $args);
            if (is_wp_error($response)) {
                throw new Exception('HTTP Request Error: ' . $response->get_error_message());
            }

            return [
                'status' => (int) wp_remote_retrieve_response_code($response),
                'body' => (string) wp_remote_retrieve_body($response),
                'headers' => (array) wp_remote_retrieve_headers($response),
            ];
        }

        // Isolated cURL fallback
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $curlHeaders = ['Content-Type: application/json'];
        foreach ($headers as $k => $v) {
            $curlHeaders[] = "$k: $v";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);

        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $result = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $status,
            'body' => (string) $result,
            'headers' => [],
        ];
    }
}
