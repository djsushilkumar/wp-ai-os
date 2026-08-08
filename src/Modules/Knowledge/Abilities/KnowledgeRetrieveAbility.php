<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Abilities;

use WPAIOS\Modules\Knowledge\KnowledgeManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: knowledge/retrieve
 */
class KnowledgeRetrieveAbility extends AbstractAbility
{
    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_knowledge_retrieve';
    }

    public function getDescription(): string
    {
        return 'Retrieve compact AI context and formatted citations for a query.';
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
        $context = $this->manager->getContextBuilder()->buildContext($chunks);

        return array_merge(['success' => true], $context);
    }
}
