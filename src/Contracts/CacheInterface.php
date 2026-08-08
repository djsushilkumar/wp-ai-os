<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Enterprise Cache Interface contract supporting multiple cache backends.
 */
interface CacheInterface
{
    /**
     * Retrieve an item from the cache.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Store an item in the cache for a given duration in seconds.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $ttl Time to live in seconds (0 for indefinite).
     * @return bool
     */
    public function set(string $key, mixed $value, int $ttl = 3600): bool;

    /**
     * Check if an item exists in the cache.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * Clear all items from the current cache store.
     *
     * @return bool
     */
    public function clear(): bool;

    /**
     * Get an item from cache, or execute closure and store the result if key does not exist.
     *
     * @param string   $key
     * @param int      $ttl
     * @param callable $callback
     * @return mixed
     */
    public function remember(string $key, int $ttl, callable $callback): mixed;
}
