<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Abilities;

use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\Elementor\ElementorManager;

/**
 * Elementor Inspector Ability — reads and analyzes existing Elementor page structures.
 */
class ElementorInspectorAbility extends AbstractAbility
{
    protected string $category = 'Elementor';
    protected array $permissions = ['edit_pages'];

    public function __construct(private ElementorManager $elementorManager)
    {
    }

    public function id(): string
    {
        return 'wp_ai_os_elementor_inspect';
    }

    public function name(): string
    {
        return 'Elementor Layout Inspector';
    }

    public function description(): string
    {
        return 'Inspect and analyze an existing Elementor page structure, returning the full AST JSON for AI analysis.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'page_id' => ['type' => 'integer', 'description' => 'Elementor page post ID to inspect.'],
            ],
            'required' => ['page_id'],
        ];
    }

    public function execute(array $params): mixed
    {
        $pageId = (int) ($params['page_id'] ?? 0);
        $ast = $this->elementorManager->pageApi->getPage($pageId);
        $snapshots = $this->elementorManager->revisionManager->listSnapshots($pageId);

        $post = get_post($pageId);

        return [
            'page_id' => $pageId,
            'title' => $post->post_title ?? 'Unknown',
            'status' => $post->post_status ?? 'unknown',
            'elementor_active' => $this->elementorManager->isElementorActive(),
            'element_count' => is_array($ast) ? count($ast) : 0,
            'ast' => $ast,
            'snapshots_available' => count($snapshots),
        ];
    }
}
