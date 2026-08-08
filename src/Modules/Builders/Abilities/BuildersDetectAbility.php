<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builders/detect
 */
class BuildersDetectAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builders_detect';
    }

    public function getDescription(): string
    {
        return 'Detect active page builder engine and active WordPress theme.';
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
        $primary = $this->manager->getDiscovery()->getPrimaryAdapter();
        return [
            'success' => true,
            'primary_builder' => $primary ? $primary->getSlug() : 'gutenberg',
            'active_adapters' => array_keys($this->manager->getDiscovery()->getActiveAdapters()),
        ];
    }
}
