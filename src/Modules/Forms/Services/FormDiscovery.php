<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Services;

use WPAIOS\Modules\Forms\Contracts\FormProviderInterface;

/**
 * Class FormDiscovery
 * Responsible for detecting active WordPress form provider plugins.
 */
class FormDiscovery
{
    /**
     * @param FormProviderInterface[] $adapters
     */
    public function __construct(private array $adapters = [])
    {
    }

    public function registerAdapter(FormProviderInterface $adapter): void
    {
        $this->adapters[$adapter->getSlug()] = $adapter;
    }

    /**
     * @return FormProviderInterface[]
     */
    public function getActiveAdapters(): array
    {
        return array_filter($this->adapters, fn ($adapter) => $adapter->isAvailable());
    }

    public function getPrimaryAdapter(): ?FormProviderInterface
    {
        $active = $this->getActiveAdapters();
        if (!empty($active)) {
            return reset($active);
        }
        return $this->adapters['wp_ai_os_native'] ?? null;
    }

    public function getAdapter(string $slug): ?FormProviderInterface
    {
        return $this->adapters[$slug] ?? null;
    }

    public function discoverProviders(): array
    {
        $report = [];
        foreach ($this->adapters as $slug => $adapter) {
            $isAvailable = $adapter->isAvailable();
            $report[$slug] = [
                'slug' => $slug,
                'name' => $adapter->getName(),
                'status' => $isAvailable ? 'active' : 'inactive',
                'version' => $adapter->getVersion(),
                'capabilities' => $adapter->getCapabilities()->toArray(),
            ];
        }
        return $report;
    }
}
