<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Abilities;

use WPAIOS\Modules\Knowledge\KnowledgeManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: knowledge/sources/list
 */
class SourcesListAbility extends AbstractAbility
{
    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_knowledge_sources_list';
    }

    public function getDescription(): string
    {
        return 'List all configured knowledge base connectors and sources.';
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
            'sources' => [
                ['id' => 'wp_content_connector', 'type' => 'wordpress', 'name' => 'WordPress Posts, Pages & Products'],
                ['id' => 'file_connector', 'type' => 'file', 'name' => 'Document Files (TXT, PDF, CSV, JSON)'],
                ['id' => 'url_connector', 'type' => 'external_url', 'name' => 'SSRF-Protected External URLs'],
            ],
        ];
    }
}
