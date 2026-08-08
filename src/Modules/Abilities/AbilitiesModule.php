<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\Abilities\Providers\AbilitiesServiceProvider;

/**
 * Ability Framework Module.
 */
class AbilitiesModule extends AbstractModule
{
    private ?AbilitiesServiceProvider $provider = null;

    public function getName(): string
    {
        return 'abilities';
    }

    public function getTitle(): string
    {
        return 'WP AI OS Ability Framework';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new AbilitiesServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
