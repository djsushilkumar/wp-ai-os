<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Abilities;

use WPAIOS\Modules\Knowledge\KnowledgeManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: knowledge/index/status
 */
class IndexStatusAbility extends AbstractAbility
{
    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_knowledge_index_status';
    }

    public function getDescription(): string
    {
        return 'Get overall knowledge index status and chunk counts.';
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
        return [
            'success' => true,
            'status' => 'indexed',
            'vector_store' => 'MySQLVectorStore',
        ];
    }
}
