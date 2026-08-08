<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Widgets\Drivers;

use WPAIOS\Modules\Elementor\Widgets\AbstractWidgetBuilder;

/**
 * Elementor Text Editor Widget Builder.
 */
class TextEditorWidget extends AbstractWidgetBuilder
{
    public function widgetType(): string
    {
        return 'text-editor';
    }

    public function create(string $editorContent, array $extraSettings = [], ?string $id = null): array
    {
        $settings = array_merge([
            'editor' => $editorContent,
        ], $extraSettings);

        return $this->build($settings, $id);
    }
}
