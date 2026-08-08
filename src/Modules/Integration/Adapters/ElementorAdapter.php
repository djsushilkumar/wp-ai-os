<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Adapters;

/**
 * Elementor Plugin Adapter.
 */
class ElementorAdapter extends AbstractPluginAdapter
{
    public function id(): string
    {
        return 'elementor';
    }

    public function name(): string
    {
        return 'Elementor Website Builder';
    }

    public function detect(): bool
    {
        return defined('ELEMENTOR_VERSION') || class_exists('\Elementor\Plugin');
    }
}
