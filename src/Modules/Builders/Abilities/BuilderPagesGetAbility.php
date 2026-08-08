<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builder/pages/get
 */
class BuilderPagesGetAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builder_pages_get';
    }

    public function getDescription(): string
    {
        return 'Get normalized builder document for a page ID.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['page_id'],
            'properties' => [
                'page_id' => ['type' => 'string', 'description' => 'Page ID'],
                'builder' => ['type' => 'string', 'description' => 'Target builder slug'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $id = $params['page_id'];
        $slug = $params['builder'] ?? 'elementor';

        $adapter = $this->manager->getRegistry()->get($slug);
        $doc = $adapter ? $adapter->getDocument($id) : null;

        if (!$doc) {
            return ['success' => false, 'error' => 'Page layout document not found'];
        }

        return [
            'success' => true,
            'document' => $doc->toArray(),
        ];
    }
}
