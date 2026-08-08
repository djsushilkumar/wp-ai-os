<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Builders\Export\BuilderExporter;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builder/export
 */
class BuilderExportAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builder_export';
    }

    public function getDescription(): string
    {
        return 'Export builder layout document to normalized JSON schema.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['page_id'],
            'properties' => [
                'page_id' => ['type' => 'string', 'description' => 'Page ID'],
                'builder' => ['type' => 'string', 'description' => 'Builder slug'],
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
            return ['success' => false, 'error' => 'Document not found'];
        }

        $exporter = new BuilderExporter();
        return [
            'success' => true,
            'export' => $exporter->export($doc),
        ];
    }
}
