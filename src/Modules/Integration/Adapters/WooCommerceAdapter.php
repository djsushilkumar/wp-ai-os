<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Adapters;

/**
 * WooCommerce Plugin Adapter.
 */
class WooCommerceAdapter extends AbstractPluginAdapter
{
    public function id(): string
    {
        return 'woocommerce';
    }

    public function name(): string
    {
        return 'WooCommerce';
    }

    public function detect(): bool
    {
        return class_exists('WooCommerce') || defined('WC_PLUGIN_FILE');
    }
}
