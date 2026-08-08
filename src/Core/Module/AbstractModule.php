<?php

declare(strict_types=1);

namespace WPAIOS\Core\Module;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Contracts\ModuleInterface;

/**
 * Abstract Module base class providing default implementation for modular components.
 */
abstract class AbstractModule implements ModuleInterface
{
    protected bool $enabled   = true;
    protected string $version = '1.0.0';

    /**
     * @var string[] List of module names this module depends on.
     */
    protected array $dependencies = [];

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get module dependencies.
     *
     * @return string[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function register(ContainerInterface $container): void
    {
    }

    public function boot(ContainerInterface $container): void
    {
    }

    public function install(): void
    {
    }

    public function uninstall(): void
    {
    }

    public function update(string $fromVersion): void
    {
    }
}
