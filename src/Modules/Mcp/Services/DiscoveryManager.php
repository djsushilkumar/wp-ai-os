<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

use WPAIOS\Modules\Mcp\Abilities\AbilityRegistry;
use WPAIOS\Modules\Mcp\Prompts\PromptRegistry;
use WPAIOS\Modules\Mcp\Resources\ResourceRegistry;
use WPAIOS\Modules\Mcp\Tools\ToolRegistry;

/**
 * Discovery Manager providing complete MCP manifest payloads for tools, resources, and prompts.
 */
class DiscoveryManager
{
    public function __construct(
        private AbilityRegistry $abilityRegistry,
        private ToolRegistry $toolRegistry,
        private ResourceRegistry $resourceRegistry,
        private PromptRegistry $promptRegistry
    ) {
    }

    /**
     * Get full discovery payload for client initialization.
     *
     * @return array<string, mixed>
     */
    public function getDiscoveryManifest(): array
    {
        return [
            'abilities' => count($this->abilityRegistry->all()),
            'tools' => $this->toolRegistry->toMcpList(),
            'resources' => $this->resourceRegistry->toMcpList(),
            'prompts' => $this->promptRegistry->toMcpList(),
            'protocolVersion' => '2024-11-05',
        ];
    }
}
