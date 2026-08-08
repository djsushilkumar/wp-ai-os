<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Discovery;

use WPAIOS\Modules\Builders\Contracts\BuilderAdapterInterface;

/**
 * Class BuilderDiscovery
 * Automatic detection of installed page builders.
 */
class BuilderDiscovery
{
    /**
     * @param BuilderAdapterInterface[] $adapters
     */
    public function __construct(private array $adapters = [])
    {
    }

    public function getActiveAdapters(): array
    {
        return array_filter($this->adapters, fn ($a) => $a->isActive());
    }

    public function getPrimaryAdapter(): ?BuilderAdapterInterface
    {
        $active = $this->getActiveAdapters();
        if (!empty($active)) {
            return reset($active);
        }
        return $this->adapters['gutenberg'] ?? null;
    }
}
