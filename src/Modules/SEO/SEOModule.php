<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\SEO\Providers\SEOServiceProvider;

/**
 * Enterprise SEO Engine Platform Module.
 */
class SEOModule extends AbstractModule
{
    private ?SEOServiceProvider $provider = null;

    public function getName(): string
    {
        return 'seo';
    }

    public function getTitle(): string
    {
        return 'WP AI OS Enterprise SEO Engine';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new SEOServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
