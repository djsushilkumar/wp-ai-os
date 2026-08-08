<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Widgets\Drivers;

use WPAIOS\Modules\Elementor\Widgets\AbstractWidgetBuilder;

/**
 * Elementor Heading Widget Builder.
 */
class HeadingWidget extends AbstractWidgetBuilder
{
    public function widgetType(): string
    {
        return 'heading';
    }

    /**
     * Create Heading Widget Node.
     *
     * @param string $title
     * @param string $headerSize 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
     * @param string $align 'left', 'center', 'right'
     * @param array<string, mixed> $extraSettings
     * @param string|null $id
     * @return array<string, mixed>
     */
    public function create(string $title, string $headerSize = 'h2', string $align = 'left', array $extraSettings = [], ?string $id = null): array
    {
        $settings = array_merge([
            'title' => $title,
            'header_size' => $headerSize,
            'align' => $align,
        ], $extraSettings);

        return $this->build($settings, $id);
    }
}
