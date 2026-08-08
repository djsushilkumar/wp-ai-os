<?php

declare(strict_types=1);

namespace WPAIOS\Core\Cache;

use WPAIOS\Contracts\CacheInterface;
use WPAIOS\Core\Cache\Drivers\CacheDriverInterface;
use WPAIOS\Core\Cache\Drivers\MemoryCacheDriver;

/**
 * Cache Manager orchestrating primary and fallback cache stores.
 */
class CacheManager implements CacheInterface
{
    /**
     * @param CacheDriverInterface      $primaryDriver
     * @param CacheDriverInterface|null $fallbackDriver
     */
    public function __construct(
        private CacheDriverInterface $primaryDriver,
        private ?CacheDriverInterface $fallbackDriver = null
    ) {
        $this->fallbackDriver = $fallbackDriver ?? new MemoryCacheDriver();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->primaryDriver->get($key, null);
        if (null !== $value) {
            return $value;
        }

        return $this->fallbackDriver->get($key, $default);
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        $this->fallbackDriver->set($key, $value, $ttl);
        return $this->primaryDriver->set($key, $value, $ttl);
    }

    public function has(string $key): bool
    {
        return null !== $this->get($key);
    }

    public function delete(string $key): bool
    {
        $this->fallbackDriver->delete($key);
        return $this->primaryDriver->delete($key);
    }

    public function clear(): bool
    {
        $this->fallbackDriver->clear();
        return $this->primaryDriver->clear();
    }

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        $cached = $this->get($key);
        if (null !== $cached) {
            return $cached;
        }

        $computed = $callback();
        $this->set($key, $computed, $ttl);
        return $computed;
    }
}
