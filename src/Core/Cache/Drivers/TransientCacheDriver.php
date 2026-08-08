<?php

declare(strict_types=1);

namespace WPAIOS\Core\Cache\Drivers;

/**
 * WordPress Transient API cache driver.
 */
class TransientCacheDriver implements CacheDriverInterface
{
    private string $prefix = 'wp_ai_os_';

    public function get(string $key, mixed $default = null): mixed
    {
        if (function_exists('get_transient')) {
            $val = get_transient($this->prefix . $key);
            return (false === $val) ? $default : $val;
        }

        return $default;
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        if (function_exists('set_transient')) {
            return set_transient($this->prefix . $key, $value, $ttl);
        }

        return false;
    }

    public function has(string $key): bool
    {
        return null !== $this->get($key);
    }

    public function delete(string $key): bool
    {
        if (function_exists('delete_transient')) {
            return delete_transient($this->prefix . $key);
        }

        return false;
    }

    public function clear(): bool
    {
        return true;
    }
}
