<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Widgets;

/**
 * Abstract Widget Builder base class for Elementor AST Nodes.
 */
abstract class AbstractWidgetBuilder
{
    /**
     * Unique widget type identifier (e.g. 'heading', 'text-editor', 'button', 'image').
     *
     * @return string
     */
    abstract public function widgetType(): string;

    /**
     * Build Elementor AST widget node structure.
     *
     * @param array<string, mixed> $settings
     * @param string|null $id Custom widget element ID.
     * @return array<string, mixed>
     */
    public function build(array $settings = [], ?string $id = null): array
    {
        $elementId = $id ?? substr(md5(uniqid('el_', true)), 0, 7);

        return [
            'id' => $elementId,
            'elType' => 'widget',
            'widgetType' => $this->widgetType(),
            'settings' => $settings,
            'elements' => [],
            'isInner' => false,
        ];
    }
}
