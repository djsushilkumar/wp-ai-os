<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builders/capabilities
 */
class BuildersCapabilitiesAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builders_capabilities';
    }

    public function getDescription(): string
    {
        return 'Get capability feature matrix across all page builders.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [],
        ];
    }

    public function execute(array $params): array
    {
        $matrix = [];
        foreach ($this->manager->getRegistry()->all() as $slug => $adapter) {
            $matrix[$slug] = $adapter->getCapabilities()->toArray();
        }

        return [
            'success' => true,
            'capabilities_matrix' => $matrix,
        ];
    }
}
