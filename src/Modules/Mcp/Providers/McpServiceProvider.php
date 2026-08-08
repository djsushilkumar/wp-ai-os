<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Providers;

use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Mcp\Abilities\AbilityRegistry;
use WPAIOS\Modules\Mcp\Admin\McpStatusDashboard;
use WPAIOS\Modules\Mcp\Prompts\PromptRegistry;
use WPAIOS\Modules\Mcp\Resources\ResourceRegistry;
use WPAIOS\Modules\Mcp\Services\AuditLogger;
use WPAIOS\Modules\Mcp\Services\AuthenticationManager;
use WPAIOS\Modules\Mcp\Services\CapabilityRegistry;
use WPAIOS\Modules\Mcp\Services\ConnectionManager;
use WPAIOS\Modules\Mcp\Services\DiscoveryManager;
use WPAIOS\Modules\Mcp\Services\HeartbeatManager;
use WPAIOS\Modules\Mcp\Services\McpBridge;
use WPAIOS\Modules\Mcp\Services\McpManager;
use WPAIOS\Modules\Mcp\Services\SchemaRegistry;
use WPAIOS\Modules\Mcp\Services\SessionManager;
use WPAIOS\Modules\Mcp\Services\TelemetryManager;
use WPAIOS\Modules\Mcp\Services\VersionCompatibility;
use WPAIOS\Modules\Mcp\Tools\ToolRegistry;
use WPAIOS\Modules\Mcp\Workflows\WorkflowEngine;
use WPAIOS\Modules\Mcp\Workflows\WorkflowRegistry;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Service Provider binding all MCP Infrastructure Registries, Managers, and Services into Container.
 */
class McpServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // 1. Registries
        $this->container->singleton(AbilityRegistry::class);
        $this->container->singleton(ToolRegistry::class);
        $this->container->singleton(ResourceRegistry::class);
        $this->container->singleton(PromptRegistry::class);
        $this->container->singleton(WorkflowRegistry::class);
        $this->container->singleton(SchemaRegistry::class);
        $this->container->singleton(CapabilityRegistry::class);

        // 2. Core Security & Management Services
        $this->container->singleton(VersionCompatibility::class);
        $this->container->singleton(SessionManager::class);
        $this->container->singleton(AuthenticationManager::class);
        $this->container->singleton(ConnectionManager::class);
        $this->container->singleton(TelemetryManager::class);

        $this->container->singleton(AuditLogger::class, function () {
            return new AuditLogger($this->container->get(LoggerInterface::class));
        });

        $this->container->singleton(HeartbeatManager::class, function () {
            return new HeartbeatManager($this->container->get(ConnectionManager::class));
        });

        $this->container->singleton(DiscoveryManager::class, function () {
            return new DiscoveryManager(
                $this->container->get(AbilityRegistry::class),
                $this->container->get(ToolRegistry::class),
                $this->container->get(ResourceRegistry::class),
                $this->container->get(PromptRegistry::class)
            );
        });

        $this->container->singleton(WorkflowEngine::class, function () {
            return new WorkflowEngine($this->container->get(LoggerInterface::class));
        });

        // 3. MCP Bridge & Manager
        $this->container->singleton(McpBridge::class, function () {
            return new McpBridge(
                $this->container->get(AbilityRegistry::class),
                $this->container->get(ToolRegistry::class),
                $this->container->get(ResourceRegistry::class),
                $this->container->get(PromptRegistry::class),
                $this->container->get(LoggerInterface::class),
                $this->container->get(EventDispatcherInterface::class)
            );
        });

        $this->container->singleton(McpManager::class, function () {
            return new McpManager(
                $this->container->get(VersionCompatibility::class),
                $this->container->get(McpBridge::class),
                $this->container->get(LoggerInterface::class),
                $this->container->get(EventDispatcherInterface::class)
            );
        });

        $this->container->singleton(McpStatusDashboard::class, function () {
            return new McpStatusDashboard(
                $this->container->get(McpManager::class)
            );
        });
    }

    public function boot(): void
    {
        /** @var McpManager $mcpManager */
        $mcpManager = $this->container->get(McpManager::class);
        $mcpManager->boot();

        /** @var McpStatusDashboard $dashboard */
        $dashboard = $this->container->get(McpStatusDashboard::class);
        $dashboard->registerHooks();
    }
}
