<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Abilities;

use WPAIOS\Modules\Knowledge\KnowledgeManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: knowledge/index/reindex
 */
class IndexReindexAbility extends AbstractAbility
{
    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_knowledge_index_reindex';
    }

    public function getDescription(): string
    {
        return 'Trigger a full or partial re-indexing of a knowledge source.';
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
            'reindexed_source' => $params['source_id'],
            'status' => 'queued',
        ];
    }
}
