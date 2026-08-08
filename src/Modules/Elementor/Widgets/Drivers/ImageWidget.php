<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Widgets\Drivers;

use WPAIOS\Modules\Elementor\Widgets\AbstractWidgetBuilder;

/**
 * Elementor Image Widget Builder.
 */
class ImageWidget extends AbstractWidgetBuilder
{
    public function widgetType(): string
    {
        return 'image';
    }

    public function create(string $imageUrl, int $imageId = 0, string $imageSize = 'full', array $extraSettings = [], ?string $id = null): array
    {
        $settings = array_merge([
            'image' => [
                'url' => $imageUrl,
                'id' => $imageId,
            ],
            'image_size' => $imageSize,
        ], $extraSettings);

        return $this->build($settings, $id);
    }
}
