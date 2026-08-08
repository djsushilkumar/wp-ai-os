<?php

declare(strict_types=1);

namespace WPAIOS\Support;

/**
 * String manipulation helper utility.
 */
class Str
{
    /**
     * Convert string to URL slug.
     *
     * @param string $title
     * @param string $separator
     * @return string
     */
    public function slug(string $title, string $separator = '-'): string
    {
        if (function_exists('sanitize_title')) {
            return sanitize_title($title);
        }

        $string = strtolower(trim($title));
        $string = preg_replace('/[^a-z0-9\-]/', $separator, $string);
        return preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, (string) $string);
    }

    /**
     * Convert string to camelCase.
     *
     * @param string $value
     * @return string
     */
    public function camel(string $value): string
    {
        $lc = lcfirst(ucwords(str_replace(['-', '_'], ' ', $value)));
        return str_replace(' ', '', $lc);
    }

    /**
     * Convert string to snake_case.
     *
     * @param string $value
     * @return string
     */
    public function snake(string $value): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }

    /**
     * Check if string starts with needle.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    /**
     * Check if string ends with needle.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }

    /**
     * Generate cryptographically secure random string.
     *
     * @param int $length
     * @return string
     */
    public function random(int $length = 16): string
    {
        return bin2hex(random_bytes((int) ceil($length / 2)));
    }
}
