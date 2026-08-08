<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\Media\Providers\MediaServiceProvider;

/**
 * Enterprise Media Platform Module.
 */
class MediaModule extends AbstractModule
{
    private ?MediaServiceProvider $provider = null;

    public function getName(): string
    {
        return 'media';
    }

    public function getTitle(): string
    {
        return 'WP AI OS Enterprise Media Platform';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new MediaServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
