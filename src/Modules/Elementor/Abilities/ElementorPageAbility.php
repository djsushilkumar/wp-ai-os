<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Abilities;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\Elementor\ElementorManager;

/**
 * Elementor Page Builder Ability — exposes full Elementor page automation to MCP agents.
 */
class ElementorPageAbility extends AbstractAbility
{
    protected string $category = 'Elementor';
    protected array $permissions = ['edit_pages'];

    public function __construct(private ElementorManager $elementorManager)
    {
    }

    public function id(): string
    {
        return 'wp_ai_os_elementor_page';
    }

    public function name(): string
    {
        return 'Elementor Page Automation';
    }

    public function description(): string
    {
        return 'Create, update, duplicate, delete, export, and import Elementor pages using structured JSON definitions.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => ['type' => 'string', 'enum' => ['create', 'update', 'get', 'delete', 'duplicate', 'publish', 'draft', 'export', 'import', 'rollback', 'list_snapshots']],
                'page_id' => ['type' => 'integer', 'description' => 'Existing page post ID (required for update, get, delete, duplicate, publish, draft, export, rollback, list_snapshots).'],
                'title' => ['type' => 'string', 'description' => 'Page title (required for create, optional for update/duplicate).'],
                'status' => ['type' => 'string', 'enum' => ['publish', 'draft', 'private'], 'default' => 'publish'],
                'page_definition' => [
                    'type' => 'object',
                    'description' => 'AI-generated JSON page definition with sections/containers/widgets.',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'sections' => ['type' => 'array'],
                    ],
                ],
                'json' => ['type' => 'string', 'description' => 'Raw JSON string for import action.'],
                'snapshot_key' => ['type' => 'string', 'description' => 'Snapshot key for rollback action.'],
            ],
            'required' => ['action'],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'];
        $pageId = (int) ($params['page_id'] ?? 0);

        return match ($action) {
            'create' => $this->elementorManager->buildAndCreatePage(
                $params['title'] ?? 'New AI Page',
                $params['page_definition'] ?? [],
                $params['status'] ?? 'publish'
            ),
            'update' => $this->elementorManager->buildAndUpdatePage(
                $pageId,
                $params['page_definition'] ?? [],
                $params['title'] ?? null,
                $params['status'] ?? null
            ),
            'get' => $this->elementorManager->pageApi->getPage($pageId),
            'delete' => $this->elementorManager->pageApi->deletePage($pageId),
            'duplicate' => $this->elementorManager->pageApi->duplicatePage($pageId, $params['title'] ?? '', $params['status'] ?? 'draft'),
            'publish' => $this->elementorManager->pageApi->publishPage($pageId),
            'draft' => $this->elementorManager->pageApi->draftPage($pageId),
            'export' => ['json' => $this->elementorManager->exportManager->exportPage($pageId)],
            'import' => ['page_id' => $this->elementorManager->importManager->importFromJson($params['json'] ?? '{}', $params['status'] ?? 'draft')],
            'rollback' => $this->elementorManager->revisionManager->rollback($pageId, $params['snapshot_key'] ?? ''),
            'list_snapshots' => $this->elementorManager->revisionManager->listSnapshots($pageId),
            default => throw new Exception("Unknown Elementor action: {$action}"),
        };
    }
}
