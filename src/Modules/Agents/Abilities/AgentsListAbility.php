<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/list
 */
class AgentsListAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_list';
    }

    public function getDescription(): string
    {
        return 'List all registered built-in and specialized AI agents.';
    }

    public function getInputSchema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [],
        ];
    }

    public function execute(array $params): array
    {
        $list = $this->manager->getRegistry()->listSummary();
        return [
            'success' => true,
            'count'   => count($list),
            'agents'  => $list,
        ];
    }
}
