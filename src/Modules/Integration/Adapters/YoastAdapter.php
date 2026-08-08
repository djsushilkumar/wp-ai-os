<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Adapters;

/**
 * Yoast SEO Adapter.
 */
class YoastAdapter extends AbstractPluginAdapter
{
    public function id(): string
    {
        return 'yoast';
    }

    public function name(): string
    {
        return 'Yoast SEO';
    }

    public function detect(): bool
    {
        return defined('WPSEO_VERSION') || class_exists('WPSEO_Options');
    }
}
