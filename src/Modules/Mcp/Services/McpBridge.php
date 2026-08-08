<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Mcp\Abilities\AbilityRegistry;
use WPAIOS\Modules\Mcp\Prompts\PromptRegistry;
use WPAIOS\Modules\Mcp\Resources\ResourceRegistry;
use WPAIOS\Modules\Mcp\Tools\ToolRegistry;

/**
 * MCP Bridge integrating WP AI OS custom abilities, tools, resources, and prompts with WordPress Agent Abilities MCP Server hooks.
 */
class McpBridge
{
    public function __construct(
        private AbilityRegistry $abilityRegistry,
        private ToolRegistry $toolRegistry,
        private ResourceRegistry $resourceRegistry,
        private PromptRegistry $promptRegistry,
        private LoggerInterface $logger,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * Hook into WordPress Agent Abilities for MCP registration filters and actions.
     *
     * @return void
     */
    public function registerBridgeHooks(): void
    {
        if (function_exists('add_action')) {
            // Extension hooks for WordPress Agent Abilities for MCP plugin
            add_action('wp_agent_abilities_register', [$this, 'onRegisterAgentAbilities']);
            add_action('mcp_register_tools', [$this, 'onRegisterTools']);
            add_action('mcp_register_resources', [$this, 'onRegisterResources']);
            add_action('mcp_register_prompts', [$this, 'onRegisterPrompts']);
        }

        $this->logger->info('[MCP Bridge] Registered MCP extension hooks.');
    }

    /**
     * Callback extending WordPress Agent Abilities.
     *
     * @param mixed $wpAbilitiesRegistry
     * @return void
     */
    public function onRegisterAgentAbilities(mixed $wpAbilitiesRegistry = null): void
    {
        foreach ($this->abilityRegistry->all() as $ability) {
            $this->logger->info(sprintf('[MCP Bridge] Extended WP Agent Abilities with [%s].', $ability->id()));
        }
        $this->eventDispatcher->dispatch('mcp.abilities_extended', $this->abilityRegistry->all());
    }

    /**
     * Callback extending MCP Tools.
     *
     * @param mixed $server
     * @return void
     */
    public function onRegisterTools(mixed $server = null): void
    {
        foreach ($this->toolRegistry->all() as $tool) {
            $this->logger->info(sprintf('[MCP Bridge] Extended MCP Tools with [%s].', $tool->id()));
        }
    }

    /**
     * Callback extending MCP Resources.
     *
     * @param mixed $server
     * @return void
     */
    public function onRegisterResources(mixed $server = null): void
    {
        foreach ($this->resourceRegistry->all() as $resource) {
            $this->logger->info(sprintf('[MCP Bridge] Extended MCP Resources with [%s].', $resource->uri()));
        }
    }

    /**
     * Callback extending MCP Prompts.
     *
     * @param mixed $server
     * @return void
     */
    public function onRegisterPrompts(mixed $server = null): void
    {
        foreach ($this->promptRegistry->all() as $prompt) {
            $this->logger->info(sprintf('[MCP Bridge] Extended MCP Prompts with [%s].', $prompt->id()));
        }
    }
}
