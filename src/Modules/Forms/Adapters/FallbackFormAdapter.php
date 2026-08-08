<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Adapters;

/**
 * Class FallbackFormAdapter
 * Native fallback form engine when no third-party form plugin is installed.
 */
class FallbackFormAdapter extends AbstractFormAdapter
{
    public function getSlug(): string
    {
        return 'wp_ai_os_native';
    }

    public function getName(): string
    {
        return 'WP AI OS Native Forms Engine';
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function getVersion(): ?string
    {
        return '1.0.0';
    }
}
