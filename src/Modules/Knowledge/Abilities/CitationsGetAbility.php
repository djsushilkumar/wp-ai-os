<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Abilities;

use WPAIOS\Modules\Knowledge\KnowledgeManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: knowledge/citations
 */
class CitationsGetAbility extends AbstractAbility
{
    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_knowledge_citations';
    }

    public function getDescription(): string
    {
        return 'Retrieve source citations for specific chunk IDs.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['query'],
            'properties' => [
                'query' => ['type' => 'string', 'description' => 'Query to fetch citations for'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $query = $params['query'];
        $chunks = $this->manager->getRetriever()->search($query, 5);
        $context = $this->manager->getContextBuilder()->buildContext($chunks);

        return [
            'success' => true,
            'citations' => $context['citations'],
        ];
    }
}
