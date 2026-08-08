<?php

declare(strict_types=1);

namespace WPAIOS\Core\Cache\Drivers;

/**
 * In-memory array cache driver.
 */
class MemoryCacheDriver implements CacheDriverInterface
{
    /**
     * @var array<string, array{value: mixed, expiresAt: int}>
     */
    private array $storage = [];

    public function get(string $key, mixed $default = null): mixed
    {
        if (! isset($this->storage[ $key ])) {
            return $default;
        }

        $item = $this->storage[ $key ];
        if ($item['expiresAt'] > 0 && time() > $item['expiresAt']) {
            unset($this->storage[ $key ]);
            return $default;
        }

        return $item['value'];
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        $expiresAt             = ($ttl > 0) ? time() + $ttl : 0;
        $this->storage[ $key ] = [
            'value'     => $value,
            'expiresAt' => $expiresAt,
        ];
        return true;
    }

    public function has(string $key): bool
    {
        return null !== $this->get($key);
    }

    public function delete(string $key): bool
    {
        unset($this->storage[ $key ]);
        return true;
    }

    public function clear(): bool
    {
        $this->storage = [];
        return true;
    }
}
