<?php

declare(strict_types=1);

namespace WPAIOS\Support;

use Exception;

/**
 * Exception-safe JSON encoding/decoding utility.
 */
class Json
{
    /**
     * Encode data to JSON string safely.
     *
     * @param mixed $data
     * @param int $flags
     * @return string
     * @throws Exception
     */
    public function encode(mixed $data, int $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE): string
    {
        $encoded = json_encode($data, $flags);
        if (false === $encoded) {
            throw new Exception('JSON Encode Error: ' . json_last_error_msg());
        }

        return $encoded;
    }

    /**
     * Decode JSON string to associative array safely.
     *
     * @param string $json
     * @return array<string, mixed>
     * @throws Exception
     */
    public function decode(string $json): array
    {
        if (empty(trim($json))) {
            return [];
        }

        $decoded = json_decode($json, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new Exception('JSON Decode Error: ' . json_last_error_msg());
        }

        return is_array($decoded) ? $decoded : [];
    }
}
