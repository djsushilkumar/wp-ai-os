<?php

declare(strict_types=1);

namespace WPAIOS\Support;

/**
 * URL utility class.
 */
class Url
{
    /**
     * Build URL with query arguments.
     *
     * @param string $url
     * @param array<string, mixed> $args
     * @return string
     */
    public function addQueryArgs(string $url, array $args): string
    {
        if (function_exists('add_query_arg')) {
            return add_query_arg($args, $url);
        }

        $query = http_build_query($args);
        $delimiter = str_contains($url, '?') ? '&' : '?';
        return $url . $delimiter . $query;
    }

    /**
     * Get home site URL.
     *
     * @param string $path
     * @return string
     */
    public function siteUrl(string $path = ''): string
    {
        if (function_exists('site_url')) {
            return site_url($path);
        }

        return '/' . ltrim($path, '/');
    }
}
