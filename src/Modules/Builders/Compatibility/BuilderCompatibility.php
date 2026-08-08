<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Compatibility;

use WPAIOS\Modules\Builders\Registry\BuilderRegistry;

/**
 * Class BuilderCompatibility
 */
class BuilderCompatibility
{
    public function __construct(private BuilderRegistry $registry)
    {
    }

    public function getCompatibilityMatrix(): array
    {
        $matrix = [];
        foreach ($this->registry->all() as $slug => $adapter) {
            $matrix[$slug] = [
                'name' => $adapter->getName(),
                'compatible' => true,
                'status' => $adapter->isActive() ? 'active' : ($adapter->isInstalled() ? 'installed' : 'unsupported_stub'),
                'version' => $adapter->getVersion() ?? 'N/A',
            ];
        }
        return $matrix;
    }
}
