<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Adapters;

use WPAIOS\Modules\Integration\Contracts\PluginAdapterInterface;

/**
 * Abstract Plugin Adapter base class providing default fallbacks for abilities, tools, and health.
 */
abstract class AbstractPluginAdapter implements PluginAdapterInterface
{
    protected string $minVersion = '1.0.0';
    /** @var string[] */
    protected array $permissions = ['manage_options'];

    public function minVersion(): string
    {
        return $this->minVersion;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    public function boot(): void
    {
        // Default boot logic
    }

    public function health(): array
    {
        $detected = $this->detect();
        return [
            'status' => $detected ? 'active' : 'inactive',
            'message' => $detected ? sprintf('Adapter [%s] is active.', $this->name()) : sprintf('Target plugin for [%s] not detected.', $this->name()),
            'version' => $detected ? 'detected' : 'none',
        ];
    }

    public function abilities(): array
    {
        return [];
    }

    public function tools(): array
    {
        return [];
    }

    public function resources(): array
    {
        return [];
    }

    public function shutdown(): void
    {
        // Default shutdown logic
    }
}
