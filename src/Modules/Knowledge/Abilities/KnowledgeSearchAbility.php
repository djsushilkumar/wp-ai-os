<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Abilities;

use WPAIOS\Modules\Knowledge\KnowledgeManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: knowledge/search
 */
class KnowledgeSearchAbility extends AbstractAbility
{
    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_knowledge_search';
    }

    public function getDescription(): string
    {
        return 'Execute a hybrid keyword and semantic vector search across the knowledge base.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['query'],
            'properties' => [
                'query' => ['type' => 'string', 'description' => 'Search query'],
                'top_k' => ['type' => 'integer', 'default' => 5],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $query = $params['query'];
        $topK = (int) ($params['top_k'] ?? 5);

        $chunks = $this->manager->getRetriever()->search($query, $topK);
        $results = array_map(fn ($c) => $c->toArray(), $chunks);

        return [
            'success' => true,
            'count' => count($results),
            'results' => $results,
        ];
    }
}
