<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\Elementor\Providers\ElementorServiceProvider;

/**
 * Elementor Automation Platform Module.
 */
class ElementorModule extends AbstractModule
{
    private ?ElementorServiceProvider $provider = null;

    public function getName(): string
    {
        return 'elementor';
    }

    public function getTitle(): string
    {
        return 'WP AI OS Elementor Automation Engine';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new ElementorServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
