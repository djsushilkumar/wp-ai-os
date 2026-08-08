<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Widgets\Drivers;

use WPAIOS\Modules\Elementor\Widgets\AbstractWidgetBuilder;

/**
 * Elementor Button Widget Builder.
 */
class ButtonWidget extends AbstractWidgetBuilder
{
    public function widgetType(): string
    {
        return 'button';
    }

    public function create(string $text, string $url = '#', string $type = 'default', string $align = 'left', array $extraSettings = [], ?string $id = null): array
    {
        $settings = array_merge([
            'text' => $text,
            'link' => ['url' => $url, 'is_external' => '', 'nofollow' => ''],
            'button_type' => $type,
            'align' => $align,
        ], $extraSettings);

        return $this->build($settings, $id);
    }
}
