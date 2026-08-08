<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\WooCommerce\Providers\WooCommerceServiceProvider;

/**
 * WooCommerce Enterprise Platform Module.
 */
class WooCommerceModule extends AbstractModule
{
    private ?WooCommerceServiceProvider $provider = null;

    public function getName(): string
    {
        return 'woocommerce';
    }

    public function getTitle(): string
    {
        return 'WP AI OS WooCommerce Enterprise Module';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new WooCommerceServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
