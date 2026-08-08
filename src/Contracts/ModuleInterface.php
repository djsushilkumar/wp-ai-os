<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Module Interface contract for modular platform components.
 */
interface ModuleInterface
{
    /**
     * Get unique module identifier string.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get human-readable module title.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Check if module is currently enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Register module bindings, abilities, or services into the container.
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function register(ContainerInterface $container): void;

    /**
     * Boot module services after registration.
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function boot(ContainerInterface $container): void;
}
