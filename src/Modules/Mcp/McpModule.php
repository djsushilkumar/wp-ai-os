<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp;

use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Core\Module\AbstractModule;
use WPAIOS\Modules\Mcp\Providers\McpServiceProvider;

/**
 * MCP Infrastructure Platform Module.
 */
class McpModule extends AbstractModule
{
    private ?McpServiceProvider $provider = null;

    public function getName(): string
    {
        return 'mcp';
    }

    public function getTitle(): string
    {
        return 'Model Context Protocol (MCP) Infrastructure';
    }

    public function register(ContainerInterface $container): void
    {
        $this->provider = new McpServiceProvider($container);
        $this->provider->register();
    }

    public function boot(ContainerInterface $container): void
    {
        $this->provider?->boot();
    }
}
