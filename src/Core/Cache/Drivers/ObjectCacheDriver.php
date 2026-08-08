<?php

declare(strict_types=1);

namespace WPAIOS\Core\Cache\Drivers;

/**
 * WordPress Object Cache API driver (compatible with Redis/Memcached object caching plugins).
 */
class ObjectCacheDriver implements CacheDriverInterface
{
    private string $group = 'wp_ai_os';

    public function get(string $key, mixed $default = null): mixed
    {
        if (function_exists('wp_cache_get')) {
            $found = false;
            $val   = wp_cache_get($key, $this->group, false, $found);
            return $found ? $val : $default;
        }

        return $default;
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        if (function_exists('wp_cache_set')) {
            return wp_cache_set($key, $value, $this->group, $ttl);
        }

        return false;
    }

    public function has(string $key): bool
    {
        return null !== $this->get($key);
    }

    public function delete(string $key): bool
    {
        if (function_exists('wp_cache_delete')) {
            return wp_cache_delete($key, $this->group);
        }

        return false;
    }

    public function clear(): bool
    {
        if (function_exists('wp_cache_flush_group')) {
            return wp_cache_flush_group($this->group);
        }

        return false;
    }
}
