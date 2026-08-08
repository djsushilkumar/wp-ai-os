<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders;

use WPAIOS\Modules\Builders\Discovery\BuilderDiscovery;
use WPAIOS\Modules\Builders\Registry\BuilderRegistry;

/**
 * Class BuildersManager
 * Central facade for Multi-Builder Abstraction Layer.
 */
class BuildersManager
{
    public function __construct(
        private BuilderRegistry $registry,
        private BuilderDiscovery $discovery
    ) {
    }

    public function getRegistry(): BuilderRegistry
    {
        return $this->registry;
    }

    public function getDiscovery(): BuilderDiscovery
    {
        return $this->discovery;
    }
}
