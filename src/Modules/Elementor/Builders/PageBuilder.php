<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Builders;

use WPAIOS\Modules\Elementor\Style\StyleEngine;

/**
 * Page Builder — assembles a full Elementor page AST from structured JSON input.
 *
 * AI generates JSON → PageBuilder converts JSON → Elementor _elementor_data meta.
 */
class PageBuilder
{
    public function __construct(
        private ContainerBuilder $containerBuilder,
        private SectionBuilder $sectionBuilder,
        private StyleEngine $styleEngine
    ) {
    }

    /**
     * Build a complete Elementor page AST from a structured JSON definition.
     *
     * @param array<string, mixed> $pageDefinition
     * @return array<string, mixed> Complete Elementor page AST ready for _elementor_data.
     */
    public function buildFromDefinition(array $pageDefinition): array
    {
        $elements = [];
        $sections = $pageDefinition['sections'] ?? [];

        foreach ($sections as $sectionDef) {
            $type = $sectionDef['type'] ?? 'container';
            $children = $sectionDef['children'] ?? [];

            if ($type === 'container') {
                $elements[] = $this->containerBuilder->createContainer(
                    children: $this->resolveChildren($children),
                    flexDirection: $sectionDef['flex_direction'] ?? 'column',
                    contentWidth: $sectionDef['content_width'] ?? 'boxed',
                    extraSettings: $sectionDef['settings'] ?? [],
                    id: $sectionDef['id'] ?? null
                );
            } elseif ($type === 'section') {
                $columns = [];
                foreach ($sectionDef['columns'] ?? [] as $colDef) {
                    $columns[] = $this->sectionBuilder->createColumn(
                        elements: $this->resolveChildren($colDef['children'] ?? []),
                        size: $colDef['size'] ?? 10,
                        extraSettings: $colDef['settings'] ?? [],
                        id: $colDef['id'] ?? null
                    );
                }
                $elements[] = $this->sectionBuilder->createSection(
                    columns: $columns,
                    extraSettings: $sectionDef['settings'] ?? [],
                    id: $sectionDef['id'] ?? null
                );
            }
        }

        return [
            'version' => '0.4',
            'title' => $pageDefinition['title'] ?? 'WP AI OS Page',
            'type' => $pageDefinition['template_type'] ?? 'page',
            'content' => $elements,
            'page_settings' => $pageDefinition['page_settings'] ?? [],
        ];
    }

    /**
     * Resolve child element definitions into AST nodes.
     *
     * @param array<array<string, mixed>> $children
     * @return array<array<string, mixed>>
     */
    private function resolveChildren(array $children): array
    {
        $resolved = [];

        foreach ($children as $child) {
            $childType = $child['type'] ?? 'widget';

            if ($childType === 'container') {
                $resolved[] = $this->containerBuilder->createContainer(
                    children: $this->resolveChildren($child['children'] ?? []),
                    flexDirection: $child['flex_direction'] ?? 'column',
                    contentWidth: $child['content_width'] ?? 'full',
                    extraSettings: $child['settings'] ?? [],
                    id: $child['id'] ?? null
                );
            } else {
                // Widget node — pass through raw definition
                $elementId = $child['id'] ?? substr(md5(uniqid('wgt_', true)), 0, 7);
                $resolved[] = [
                    'id' => $elementId,
                    'elType' => 'widget',
                    'widgetType' => $child['widget_type'] ?? 'heading',
                    'settings' => $child['settings'] ?? [],
                    'elements' => [],
                    'isInner' => false,
                ];
            }
        }

        return $resolved;
    }
}
