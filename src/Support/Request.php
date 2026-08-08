<?php

declare(strict_types=1);

namespace WPAIOS\Support;

/**
 * Enterprise Request wrapper encapsulating HTTP Superglobals and REST requests.
 */
class Request
{
    /**
     * @param array<string, mixed> $queryParams
     * @param array<string, mixed> $postParams
     * @param array<string, mixed> $headers
     * @param string $body
     */
    public function __construct(
        private array $queryParams = [],
        private array $postParams = [],
        private array $headers = [],
        private string $body = ''
    ) {
        if (empty($queryParams)) {
            $this->queryParams = $_GET;
        }
        if (empty($postParams)) {
            $this->postParams = $_POST;
        }
    }

    /**
     * Get parameter from query or body.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->postParams[$key] ?? $this->queryParams[$key] ?? $default;
    }

    /**
     * Get JSON decoded request body.
     *
     * @return array<string, mixed>
     */
    public function json(): array
    {
        if (empty($this->body)) {
            $this->body = (string) file_get_contents('php://input');
        }

        $data = json_decode($this->body, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Get header by name.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function header(string $key, ?string $default = null): ?string
    {
        $normalized = strtolower($key);
        foreach ($this->headers as $hKey => $hValue) {
            if (strtolower($hKey) === $normalized) {
                return (string) $hValue;
            }
        }
        return $default;
    }
}
