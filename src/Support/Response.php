<?php

declare(strict_types=1);

namespace WPAIOS\Support;

/**
 * Enterprise Response wrapper for JSON REST responses.
 */
class Response
{
    /**
     * Return JSON Success response array.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return array{success: true, message: string, data: mixed, code: int}
     */
    public function success(mixed $data = null, string $message = 'Success', int $code = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ];
    }

    /**
     * Return JSON Error response array.
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return array{success: false, message: string, errors: mixed, code: int}
     */
    public function error(string $message = 'Error', int $code = 400, mixed $errors = null): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ];
    }
}
