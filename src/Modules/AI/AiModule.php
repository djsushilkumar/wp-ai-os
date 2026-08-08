<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\AI\Providers\AiServiceProvider;

/**
 * AI Provider Framework Platform Module.
 */
class AiModule extends AbstractModule
{
    private ?AiServiceProvider $provider = null;

    public function getName(): string
    {
        return 'ai';
    }

    public function getTitle(): string
    {
        return 'WP AI OS AI Provider Framework';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new AiServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
