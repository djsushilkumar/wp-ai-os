<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\Integration\Providers\IntegrationServiceProvider;

/**
 * Universal Plugin Integration Platform Module.
 */
class IntegrationModule extends AbstractModule
{
    private ?IntegrationServiceProvider $provider = null;

    public function getName(): string
    {
        return 'integration';
    }

    public function getTitle(): string
    {
        return 'WP AI OS Universal Integration Framework';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new IntegrationServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
