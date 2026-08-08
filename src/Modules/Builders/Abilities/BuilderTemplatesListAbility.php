<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builder/templates/list
 */
class BuilderTemplatesListAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builder_templates_list';
    }

    public function getDescription(): string
    {
        return 'List saved templates for a page builder.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['builder'],
            'properties' => [
                'builder' => ['type' => 'string', 'description' => 'Builder slug (elementor, gutenberg, bricks, divi)'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $slug = $params['builder'];
        $adapter = $this->manager->getRegistry()->get($slug);

        if (!$adapter) {
            return ['success' => false, 'error' => 'Builder not found'];
        }

        return [
            'success' => true,
            'templates' => $adapter->getTemplates(),
        ];
    }
}
