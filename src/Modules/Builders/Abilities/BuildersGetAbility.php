<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builders/get
 */
class BuildersGetAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builders_get';
    }

    public function getDescription(): string
    {
        return 'Get details and capabilities of a specific page builder.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['slug'],
            'properties' => [
                'slug' => ['type' => 'string', 'description' => 'Builder slug (elementor, gutenberg, bricks, divi)'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $slug = $params['slug'];
        $adapter = $this->manager->getRegistry()->get($slug);

        if (!$adapter) {
            return ['success' => false, 'error' => 'Builder not found'];
        }

        return [
            'success' => true,
            'builder' => [
                'slug' => $adapter->getSlug(),
                'name' => $adapter->getName(),
                'installed' => $adapter->isInstalled(),
                'active' => $adapter->isActive(),
                'version' => $adapter->getVersion(),
                'capabilities' => $adapter->getCapabilities()->toArray(),
            ],
        ];
    }
}
