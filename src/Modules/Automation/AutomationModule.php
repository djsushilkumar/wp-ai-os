<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\Automation\Providers\AutomationServiceProvider;

/**
 * Autonomous Workflow Engine Platform Module.
 */
class AutomationModule extends AbstractModule
{
    private ?AutomationServiceProvider $provider = null;

    public function getName(): string
    {
        return 'automation';
    }

    public function getTitle(): string
    {
        return 'WP AI OS Autonomous Workflow Engine';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new AutomationServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
