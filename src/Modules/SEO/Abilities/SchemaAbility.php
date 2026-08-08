<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO\Abilities;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\SEO\Adapters\FallbackSEOAdapter;
use WPAIOS\Modules\SEO\Services\SchemaBuilder;

/**
 * Schema.org Ability — allows MCP agents to generate, get, and update Schema.org JSON-LD definitions.
 */
class SchemaAbility extends AbstractAbility
{
    protected string $category = 'SEO';
    protected array $permissions = ['edit_posts'];

    public function __construct(
        private FallbackSEOAdapter $adapter,
        private SchemaBuilder $schemaBuilder
    ) {
    }

    public function id(): string
    {
        return 'wp_ai_os_seo_schema';
    }

    public function name(): string
    {
        return 'Schema.org JSON-LD Manager';
    }

    public function description(): string
    {
        return 'Generate, get, or update Schema.org JSON-LD structured data (Article, Organization, FAQ, HowTo).';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => ['type' => 'string', 'enum' => ['get', 'update', 'generate_organization', 'generate_faq']],
                'post_id' => ['type' => 'integer'],
                'schema_data' => ['type' => 'object'],
                'org_name' => ['type' => 'string'],
                'org_url' => ['type' => 'string'],
                'faq_items' => ['type' => 'array'],
            ],
            'required' => ['action'],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'];
        $postId = (int) ($params['post_id'] ?? 0);

        if ($action === 'get') {
            return $this->adapter->getSchema($postId);
        }

        if ($action === 'update') {
            $schemaData = $params['schema_data'] ?? [];
            $success = $this->adapter->updateSchema($postId, $schemaData);
            return ['success' => $success];
        }

        if ($action === 'generate_organization') {
            $orgName = $params['org_name'] ?? 'My Organization';
            $orgUrl = $params['org_url'] ?? 'https://example.com';
            return $this->schemaBuilder->buildOrganization($orgName, $orgUrl);
        }

        if ($action === 'generate_faq') {
            $items = $params['faq_items'] ?? [];
            return $this->schemaBuilder->buildFAQ($items);
        }

        throw new Exception("Unknown Schema action: {$action}");
    }
}
