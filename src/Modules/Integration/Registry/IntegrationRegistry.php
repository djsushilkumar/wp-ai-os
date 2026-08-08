<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Registry;

use Exception;
use WPAIOS\Modules\Integration\Contracts\PluginAdapterInterface;

/**
 * Integration Registry managing third-party plugin adapters.
 */
class IntegrationRegistry
{
    /**
     * @var array<string, PluginAdapterInterface>
     */
    private array $adapters = [];

    public function register(PluginAdapterInterface $adapter): void
    {
        $this->adapters[$adapter->id()] = $adapter;
    }

    public function get(string $id): PluginAdapterInterface
    {
        if (!isset($this->adapters[$id])) {
            throw new Exception(sprintf('Integration adapter [%s] is not registered.', $id));
        }

        return $this->adapters[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->adapters[$id]);
    }

    /**
     * @return PluginAdapterInterface[]
     */
    public function getActiveAdapters(): array
    {
        return array_filter($this->adapters, fn (PluginAdapterInterface $adapter) => $adapter->detect());
    }

    /**
     * @return array<string, PluginAdapterInterface>
     */
    public function all(): array
    {
        return $this->adapters;
    }
}
