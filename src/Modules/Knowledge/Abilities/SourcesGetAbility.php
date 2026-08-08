<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Abilities;

use WPAIOS\Modules\Knowledge\KnowledgeManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: knowledge/sources/get
 */
class SourcesGetAbility extends AbstractAbility
{
    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_knowledge_sources_get';
    }

    public function getDescription(): string
    {
        return 'Get configuration details for a specific knowledge source connector.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['source_id'],
            'properties' => [
                'source_id' => ['type' => 'string', 'description' => 'Source Connector ID'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        return [
            'success' => true,
            'source' => [
                'id' => $params['source_id'],
                'status' => 'connected',
                'health' => 'ok',
            ],
        ];
    }
}
