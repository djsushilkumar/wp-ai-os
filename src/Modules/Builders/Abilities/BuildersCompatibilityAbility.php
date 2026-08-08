<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Builders\Compatibility\BuilderCompatibility;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builders/compatibility
 */
class BuildersCompatibilityAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builders_compatibility';
    }

    public function getDescription(): string
    {
        return 'Get builder and theme compatibility matrix.';
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
        $comp = new BuilderCompatibility($this->manager->getRegistry());
        return [
            'success' => true,
            'matrix' => $comp->getCompatibilityMatrix(),
        ];
    }
}
