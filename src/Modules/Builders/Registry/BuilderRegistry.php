<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Registry;

use WPAIOS\Modules\Builders\Contracts\BuilderAdapterInterface;

/**
 * Class BuilderRegistry
 * Registry managing all registered Builder adapters.
 */
class BuilderRegistry
{
    private array $adapters = [];

    public function register(BuilderAdapterInterface $adapter): void
    {
        $this->adapters[$adapter->getSlug()] = $adapter;
    }

    public function unregister(string $slug): void
    {
        unset($this->adapters[$slug]);
    }

    public function get(string $slug): ?BuilderAdapterInterface
    {
        return $this->adapters[$slug] ?? null;
    }

    public function has(string $slug): bool
    {
        return isset($this->adapters[$slug]);
    }

    public function all(): array
    {
        return $this->adapters;
    }

    public function detect(): array
    {
        $report = [];
        foreach ($this->adapters as $slug => $adapter) {
            $report[$slug] = [
                'slug' => $slug,
                'name' => $adapter->getName(),
                'installed' => $adapter->isInstalled(),
                'active' => $adapter->isActive(),
                'version' => $adapter->getVersion(),
                'capabilities' => $adapter->getCapabilities()->toArray(),
            ];
        }
        return $report;
    }
}
