<?php

declare(strict_types=1);

namespace WPAIOS\Core\Cache\Drivers;

/**
 * Cache Driver Interface contract.
 */
interface CacheDriverInterface
{
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value, int $ttl = 3600): bool;
    public function has(string $key): bool;
    public function delete(string $key): bool;
    public function clear(): bool;
}
