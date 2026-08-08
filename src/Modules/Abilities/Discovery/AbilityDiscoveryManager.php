<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Discovery;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Registry\AbilityRegistry;
use WPAIOS\Modules\Mcp\Tools\AbstractTool;
use WPAIOS\Modules\Mcp\Tools\ToolRegistry;

/**
 * Ability Discovery Manager automatically registering abilities with MCP ToolRegistry.
 */
class AbilityDiscoveryManager
{
    public function __construct(
        private AbilityRegistry $abilityRegistry,
        private ?ToolRegistry $toolRegistry = null,
        private ?LoggerInterface $logger = null
    ) {
    }

    /**
     * Automatically sync all registered abilities to MCP Tool Registry.
     *
     * @return int Number of synced abilities to MCP tools.
     */
    public function syncToMcp(): int
    {
        if (null === $this->toolRegistry) {
            return 0;
        }

        $synced = 0;
        foreach ($this->abilityRegistry->all() as $ability) {
            $tool = new class ($ability) extends AbstractTool {
                public function __construct(private mixed $ability)
                {
                }
                public function id(): string
                {
                    return $this->ability->id();
                }
                public function name(): string
                {
                    return $this->ability->name();
                }
                public function description(): string
                {
                    return $this->ability->description();
                }
                public function inputSchema(): array
                {
                    return $this->ability->schema();
                }
                public function execute(array $input): mixed
                {
                    return $this->ability->execute($input);
                }
            };

            $this->toolRegistry->register($tool);
            ++$synced;
        }

        $this->logger?->info(sprintf('[Ability Discovery] Synced %d abilities to MCP Tool Registry.', $synced));
        return $synced;
    }
}
