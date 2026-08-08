<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;

/**
 * Core MCP Manager orchestrating detection, graceful fallback, and MCP bridge initialization.
 */
class McpManager
{
    private bool $mcpPluginDetected = false;

    public function __construct(
        private VersionCompatibility $versionCompatibility,
        private McpBridge $mcpBridge,
        private LoggerInterface $logger,
        private EventDispatcherInterface $eventDispatcher
    ) {
        $this->detectMcpPlugin();
    }

    /**
     * Detect if WordPress Agent Abilities for MCP plugin is active.
     *
     * @return bool
     */
    public function detectMcpPlugin(): bool
    {
        $comp = $this->versionCompatibility->checkCompatibility();
        $this->mcpPluginDetected = $comp['mcp_plugin']['detected'];
        return $this->mcpPluginDetected;
    }

    /**
     * Check if MCP plugin is active.
     *
     * @return bool
     */
    public function isMcpPluginActive(): bool
    {
        return $this->mcpPluginDetected;
    }

    /**
     * Initialize MCP Infrastructure.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->isMcpPluginActive()) {
            $this->logger->info('[MCP Manager] WordPress Agent Abilities MCP plugin detected. Initializing MCP Bridge...');
            $this->mcpBridge->registerBridgeHooks();
            $this->eventDispatcher->dispatch('mcp.booted');
        } else {
            $this->logger->warning('[MCP Manager] WordPress Agent Abilities MCP plugin is missing. Running in standalone fallback mode.');
            $this->registerAdminNotice();
            $this->eventDispatcher->dispatch('mcp.fallback_mode');
        }
    }

    /**
     * Display Admin Notice if WordPress Agent Abilities MCP plugin is missing.
     *
     * @return void
     */
    private function registerAdminNotice(): void
    {
        if (function_exists('add_action')) {
            add_action('admin_notices', function (): void {
                if (function_exists('current_user_can') && current_user_can('activate_plugins')) {
                    echo '<div class="notice notice-warning is-dismissible"><p>' .
                        esc_html__('WP AI OS: WordPress Agent Abilities for MCP plugin was not detected. MCP-dependent features are safely paused. Install WordPress Agent Abilities to enable external AI Agent IDE control.', 'wp-ai-os') .
                        '</p></div>';
                }
            });
        }
    }

    public function isFallbackMode(): bool
    {
        return !$this->isMcpPluginActive();
    }
}
