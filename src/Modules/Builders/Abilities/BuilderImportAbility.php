<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Builders\Import\BuilderImporter;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builder/import
 */
class BuilderImportAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builder_import';
    }

    public function getDescription(): string
    {
        return 'Import normalized layout JSON payload into target builder.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['payload', 'target_builder'],
            'properties' => [
                'payload' => ['type' => 'object', 'description' => 'Import payload'],
                'target_builder' => ['type' => 'string', 'description' => 'Target builder slug'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $payload = $params['payload'];
        $slug = $params['target_builder'];

        $importer = new BuilderImporter();
        $doc = $importer->import($payload);

        $adapter = $this->manager->getRegistry()->get($slug);
        if (!$adapter) {
            return ['success' => false, 'error' => 'Target builder adapter not found'];
        }

        $ok = $adapter->saveDocument($doc->getId(), $doc);

        return [
            'success' => $ok,
            'imported_document_id' => $doc->getId(),
            'builder' => $slug,
        ];
    }
}
