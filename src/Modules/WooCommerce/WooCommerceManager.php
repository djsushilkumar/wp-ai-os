<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\WooCommerce\Services\InventoryManager;
use WPAIOS\Modules\WooCommerce\Services\ProductManager;

/**
 * WooCommerceManager — central facade coordinating WooCommerce enterprise services.
 */
class WooCommerceManager
{
    private bool $wooActive = false;

    public function __construct(
        public readonly ProductManager $productManager,
        public readonly InventoryManager $inventoryManager,
        public readonly LoggerInterface $logger
    ) {
    }

    public function detect(): bool
    {
        $this->wooActive = class_exists('WooCommerce') || defined('WC_PLUGIN_FILE');

        if ($this->wooActive) {
            $this->logger->info('[WooCommerceManager] WooCommerce detected and active.');
        } else {
            $this->logger->info('[WooCommerceManager] WooCommerce not detected. Running abstraction mode.');
        }

        return $this->wooActive;
    }

    public function isWooCommerceActive(): bool
    {
        return $this->wooActive;
    }
}
