<?php

declare(strict_types=1);

namespace WPAIOS\Providers;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Contracts\ServiceProviderInterface;

/**
 * Abstract Service Provider base class providing container access.
 */
abstract class AbstractServiceProvider implements ServiceProviderInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * Boot service provider logic after registration.
     *
     * @return void
     */
    public function boot(): void
    {
        // Default no-op for providers that do not require boot logic
    }
}
