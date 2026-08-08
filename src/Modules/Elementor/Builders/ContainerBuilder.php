<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Builders;

/**
 * Flexbox Container AST Node Builder.
 */
class ContainerBuilder
{
    /**
     * Build Flexbox Container Elementor AST node.
     *
     * @param array<array<string, mixed>> $children Child widgets or nested containers.
     * @param string $flexDirection 'column' or 'row'
     * @param string $contentWidth 'boxed' or 'full'
     * @param array<string, mixed> $extraSettings Flex gap, padding, margins, background.
     * @param string|null $id Custom element ID.
     * @return array<string, mixed>
     */
    public function createContainer(
        array $children = [],
        string $flexDirection = 'column',
        string $contentWidth = 'boxed',
        array $extraSettings = [],
        ?string $id = null
    ): array {
        $elementId = $id ?? substr(md5(uniqid('cont_', true)), 0, 7);

        $settings = array_merge([
            'flex_direction' => $flexDirection,
            'content_width' => $contentWidth,
            'flex_gap' => ['size' => 20, 'unit' => 'px'],
        ], $extraSettings);

        return [
            'id' => $elementId,
            'elType' => 'container',
            'settings' => $settings,
            'elements' => $children,
            'isInner' => false,
        ];
    }
}
