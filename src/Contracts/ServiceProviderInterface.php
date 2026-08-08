<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Service Provider Interface contract for registering and booting application services.
 */
interface ServiceProviderInterface
{
    /**
     * Register bindings and services into the container.
     *
     * @return void
     */
    public function register(): void;

    /**
     * Boot registered services after container bindings are established.
     *
     * @return void
     */
    public function boot(): void;
}
